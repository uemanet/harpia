#!/usr/bin/env bash
# =============================================================================
#  deploy.sh — Harpia :: Script de Deploy
#  Uso: bash deploy.sh
# =============================================================================

set -uo pipefail
# Nota: removido o -e intencional — tratamos erros manualmente para dar
# mensagens claras em vez de abortar silenciosamente.

# ── Cores ────────────────────────────────────────────────────────────────────
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
BOLD='\033[1m'
NC='\033[0m'

# ── Helpers ──────────────────────────────────────────────────────────────────
info()    { echo -e "${CYAN}[INFO]${NC}  $*"; }
success() { echo -e "${GREEN}[OK]${NC}    $*"; }
warn()    { echo -e "${YELLOW}[AVISO]${NC} $*"; }
error()   { echo -e "${RED}[ERRO]${NC}  $*" >&2; }
header()  { echo -e "\n${BOLD}${CYAN}==> $*${NC}"; }

die() {
  error "$*"
  exit 1
}

ask_yes_no() {
  local prompt="$1"
  local default="${2:-n}"
  local answer

  while true; do
    if [[ "$default" == "s" ]]; then
      read -rp "$(echo -e "${YELLOW}?${NC} $prompt [S/n]: ")" answer
      answer="${answer:-s}"
    else
      read -rp "$(echo -e "${YELLOW}?${NC} $prompt [s/N]: ")" answer
      answer="${answer:-n}"
    fi

    case "${answer,,}" in
      s|sim|y|yes) return 0 ;;
      n|nao|no)    return 1 ;;
      *)           warn "Responda 's' ou 'n'." ;;
    esac
  done
}

# ── Verifica dependências ─────────────────────────────────────────────────────
check_deps() {
  header "Verificando dependencias"
  local missing=0

  if command -v docker &>/dev/null; then
    success "docker encontrado"
  else
    error "docker nao encontrado. Instale antes de continuar."
    missing=1
  fi

  if docker compose version &>/dev/null 2>&1; then
    COMPOSE_CMD="docker compose"
    success "Docker Compose v2 (plugin) detectado"
  elif command -v docker-compose &>/dev/null; then
    COMPOSE_CMD="docker-compose"
    success "docker-compose v1 detectado"
  else
    error "Nenhuma versao do Docker Compose encontrada."
    missing=1
  fi

  [[ $missing -eq 1 ]] && die "Corrija as dependencias acima e rode novamente."
}

# ── Verifica .env ─────────────────────────────────────────────────────────────
check_env() {
  header "Verificando .env"

  if [[ -f ".env" ]]; then
    success ".env encontrado"
    return 0
  fi

  if [[ -f ".env.example" ]]; then
    warn ".env nao encontrado. Copiando de .env.example..."
    cp ".env.example" ".env" || die "Nao foi possivel copiar .env.example para .env"
    warn "Edite o .env com suas credenciais antes de continuar em producao!"
  else
    die ".env e .env.example ausentes. Impossivel continuar."
  fi
}

# ── Lê variável do .env com fallback ─────────────────────────────────────────
env_val() {
  local key="$1"
  local fallback="${2:-}"
  local val
  val=$(grep -E "^${key}=" .env 2>/dev/null | head -n1 | cut -d= -f2- | tr -d '"' || true)
  echo "${val:-$fallback}"
}

# ── Sobe os containers ────────────────────────────────────────────────────────
start_containers() {
  header "Subindo containers Docker"
  $COMPOSE_CMD up -d --build || die "Falha ao subir containers."
  success "Containers iniciados"
}

# ── Aguarda o container web ficar pronto ──────────────────────────────────────
wait_for_web() {
  header "Aguardando container web ficar pronto"
  local retries=20
  local count=0

  until $COMPOSE_CMD exec -T web php -v &>/dev/null 2>&1; do
    count=$((count + 1))
    if [[ $count -ge $retries ]]; then
      die "Container web nao ficou pronto a tempo. Verifique: $COMPOSE_CMD logs web"
    fi
    info "Aguardando PHP... ($count/$retries)"
    sleep 3
  done

  success "PHP disponivel no container"
}

# ── Composer ──────────────────────────────────────────────────────────────────
run_composer() {
  header "Dependencias PHP (Composer)"

  if ask_yes_no "Rodar 'composer install'?" "s"; then
    $COMPOSE_CMD exec -T web composer install \
      --no-interaction \
      --prefer-dist \
      --optimize-autoloader \
      || die "Composer install falhou."
    success "Composer concluido"
  else
    info "Composer pulado."
  fi
}

# ── App Key ───────────────────────────────────────────────────────────────────
generate_key() {
  header "APP_KEY"
  local current_key
  current_key=$(env_val "APP_KEY" "")

  if [[ -z "$current_key" ]]; then
    warn "APP_KEY nao definida. Gerando..."
    $COMPOSE_CMD exec -T web php artisan key:generate --force \
      || die "Falha ao gerar APP_KEY."
    success "APP_KEY gerada"
  else
    success "APP_KEY ja definida. Pulando."
  fi
}

# ── Storage link ──────────────────────────────────────────────────────────────
storage_link() {
  header "Storage link"
  $COMPOSE_CMD exec -T web php artisan storage:link 2>/dev/null || true
  success "Storage link verificado"
}

# ── Cache / Optimize ──────────────────────────────────────────────────────────
optimize() {
  header "Otimizacoes do Laravel"
  local app_env
  app_env=$(env_val "APP_ENV" "production")

  if [[ "$app_env" == "production" ]]; then
    info "Ambiente de producao — rodando optimize..."
    $COMPOSE_CMD exec -T web php artisan optimize || warn "optimize falhou (nao critico)."
    $COMPOSE_CMD exec -T web php artisan view:cache || warn "view:cache falhou (nao critico)."
    success "Cache gerado"
  else
    info "Ambiente '$app_env' — limpando caches..."
    $COMPOSE_CMD exec -T web php artisan optimize:clear || warn "optimize:clear falhou (nao critico)."
    success "Caches limpos"
  fi
}

# ── Migrations ────────────────────────────────────────────────────────────────
run_migrations() {
  header "Migrations"

  if ask_yes_no "Rodar migrations?"; then
    local force_flag=""
    if ask_yes_no "Usar '--force' (necessario em producao)?"; then
      force_flag="--force"
    fi

    $COMPOSE_CMD exec -T web php artisan migrate $force_flag \
      || die "Migrations falharam."
    success "Migrations concluidas"
  else
    info "Migrations puladas."
  fi
}

# ── Seeders ───────────────────────────────────────────────────────────────────
run_seeders() {
  header "Seeders"

  if ask_yes_no "Rodar seeders?"; then
    echo ""
    echo -e "${BOLD}Qual seeder deseja executar?${NC}"
    echo "  1) DatabaseSeeder (padrao)"
    echo "  2) Digitar classe manualmente"
    echo "  3) Pular"

    local choice
    read -rp "$(echo -e "${YELLOW}?${NC} Opcao [1/2/3]: ")" choice

    case "$choice" in
      1)
        $COMPOSE_CMD exec -T web php artisan db:seed --force \
          || die "Seeder falhou."
        success "DatabaseSeeder executado"
        ;;
      2)
        local seeder_class
        read -rp "$(echo -e "${YELLOW}?${NC} Nome da classe (ex: UsersTableSeeder): ")" seeder_class
        if [[ -n "$seeder_class" ]]; then
          $COMPOSE_CMD exec -T web php artisan db:seed --class="$seeder_class" --force \
            || die "Seeder '$seeder_class' falhou."
          success "Seeder '$seeder_class' executado"
        else
          warn "Nome vazio. Seeders pulados."
        fi
        ;;
      *)
        info "Seeders pulados."
        ;;
    esac
  else
    info "Seeders pulados."
  fi
}

# ── NPM / Assets ──────────────────────────────────────────────────────────────
run_assets() {
  header "Assets frontend (Vite)"

  if ! command -v node &>/dev/null; then
    warn "Node.js nao encontrado no host. Pulando build de assets."
    warn "Execute 'npm install && npm run build' manualmente."
    return 0
  fi

  if ask_yes_no "Rodar 'npm install && npm run build' no host?"; then
    npm install || die "npm install falhou."
    npm run build || die "npm run build falhou."
    success "Assets compilados"
  else
    info "Build de assets pulado."
  fi
}

# ── Queue worker ──────────────────────────────────────────────────────────────
check_queue() {
  header "Queue Worker"

  local status
  status=$($COMPOSE_CMD ps harpia-queue 2>/dev/null || true)

  if echo "$status" | grep -q "Up\|running"; then
    success "Container de queue em execucao"
  else
    warn "Container de queue pode nao estar rodando."
    warn "Verifique com: $COMPOSE_CMD logs harpia-queue"
  fi
}

# ── Status final ─────────────────────────────────────────────────────────────
show_status() {
  header "Status dos containers"
  $COMPOSE_CMD ps
  echo ""

  local app_port pma_port
  app_port=$(env_val "APP_PORT" "80")
  pma_port=$(env_val "PMA_PORT" "8080")

  success "Deploy concluido!"
  echo ""
  echo -e "${BOLD}Acesso:${NC}"
  echo -e "  Aplicacao:  ${GREEN}http://localhost:${app_port}${NC}"
  echo -e "  phpMyAdmin: ${GREEN}http://localhost:${pma_port}${NC}"
}

# ── Ponto de entrada ─────────────────────────────────────────────────────────
main() {
  echo ""
  echo -e "${BOLD}${CYAN}  HARPIA — Deploy Script | Laravel 12 + Docker${NC}"
  echo -e "${CYAN}  ================================================${NC}"
  echo ""

  check_deps
  check_env
  start_containers
  wait_for_web
  run_composer
  generate_key
  storage_link
  optimize
  run_migrations
  run_seeders
  run_assets
  check_queue
  show_status
}

main "$@"