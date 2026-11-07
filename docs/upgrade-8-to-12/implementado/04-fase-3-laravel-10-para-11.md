# Etapa Implementada: FASE 3 - Upgrade Laravel 10 -> 11

**Data:** Marco/2026
**Fase do Upgrade:** FASE 3 (Laravel 10 -> 11)

---

## Pre-validacao solicitada: MCP do Laravel Boost

Antes de iniciar a fase, foi validado o funcionamento do MCP/Boost no ambiente:

- Comandos Boost disponiveis no Artisan:
  - `boost:install`
  - `boost:mcp`
  - `boost:update`

- Validacao direta do comando MCP configurado:
  - `wsl.exe /usr/bin/php8.3 /home/junio/projetos/uema/harpia/artisan boost:mcp --help`
  - Resultado: comando executa corretamente e exibe help do servidor MCP.

Conclusao: o MCP do Laravel Boost esta funcionando no ambiente atual.

---

## O que foi feito

### 1) Atualizacao de dependencias (`composer.json`)

Foram aplicadas as mudancas para o alvo Laravel 11:

- `php`: `^8.1` -> `^8.2`
- `laravel/framework`: `^10.0` -> `^11.0`
- `laravel/tinker`: `^2.7` -> `^2.9`
- Removido `doctrine/dbal` da secao `require`

Ajustes de stack Symfony/Dev para compatibilidade com Laravel 11:

- `symfony/console`: `^6.0` -> `^7.0`
- `symfony/css-selector`: `^6.0` -> `^7.0`
- `symfony/dom-crawler`: `^6.0` -> `^7.0`
- `barryvdh/laravel-debugbar`: `^3.8` -> `^3.16`

### 2) Compatibilizacao de pacote customizado (`uemanet/eloquent-table`)

No pacote local em `packages/uemanet/eloquent-table/composer.json`, foram ampliadas constraints para Laravel 11:

- `illuminate/support`: `^9.0 || ^10.0 || ^11.0`
- `illuminate/database`: `^9.0 || ^10.0 || ^11.0`
- `illuminate/pagination`: `^9.0 || ^10.0 || ^11.0`

### 3) Correcoes obrigatorias da fase no codigo

- `app/Http/Kernel.php`
  - `CheckForMaintenanceMode` -> `PreventRequestsDuringMaintenance`.

- `modulos/Seguranca/Models/Usuario.php`
  - Adicionado `getAuthPasswordName()` retornando `usr_senha` para manter compatibilidade com campo de senha customizado.
  - Ajuste necessario para evitar conflito de composicao com trait `Authenticatable` no Laravel 11.

- `tests/ModulosTestCase.php`
  - Assinatura de `assertDatabaseHas` atualizada para compatibilidade com Laravel 11:
    - `array $data = []`
  - Encaminhamento de `$connection` para `parent::assertDatabaseHas(...)`.

### 4) Execucao dos comandos de fase

Executado com sucesso:

- `composer update -W --no-interaction`
- `php artisan optimize:clear`
- `php artisan route:list`

Resultado relevante do Composer:

- Laravel atualizado para `v11.48.0`
- `doctrine/dbal` removido
- Symfony atualizado para major 7 na stack do framework
- `nesbot/carbon` atualizado para `3.x`
- `uemanet/eloquent-table` atualizado via repositorio local com constraints ampliadas

---

## Validacao e observacoes

### Estado funcional da aplicacao

- `php artisan optimize:clear` executado sem erros.
- `php artisan route:list` executado com sucesso (rotas carregadas no Laravel 11).

### Testes automatizados

Validacao representativa executada:

- `./bin/phpunit modulos/Seguranca/tests/Repositories/PerfilRepositoryTest.php --stop-on-error`

Resultado atual:

- Erro de ambiente: `PDOException: could not find driver` (SQLite/PDO ausente)
- Warning: `No code coverage driver available`

Observacao:

- Nao foram encontrados erros de bootstrap do framework apos a migracao para Laravel 11.
- O bloqueio de testes continua ambiental (driver de banco/cobertura), nao por incompatibilidade primaria da fase.

---

## Checklist da FASE 3

- [x] Dependencias base atualizadas para Laravel 11
- [x] `doctrine/dbal` removido
- [x] Pacote customizado (`uemanet/eloquent-table`) compatibilizado para Illuminate 11
- [x] Middleware de manutencao atualizado no Kernel
- [x] Ajuste de senha customizada em `Usuario`
- [x] Ajuste de assinatura de teste base (`ModulosTestCase`)
- [x] Aplicacao sobe e lista rotas no Laravel 11
- [ ] Testes automatizados verdes (bloqueado por ambiente sem PDO SQLite e sem driver de cobertura)

---

## Pendencias para consolidacao

1. Revisar migracao de factories legadas para class-based factories como melhoria estrutural (nao bloqueou este upgrade por compatibilidade atual do pacote `laravel/legacy-factories`).
2. Ajustar ambiente de testes:
   - Habilitar `pdo_sqlite` no PHP de CI/dev, ou
   - Migrar teste para banco disponivel no ambiente.

---

## Proxima etapa

Iniciar FASE 4 (Laravel 11 -> 12), com foco em:

1. Atualizacao final de dependencias para Laravel 12.
2. Validacao de impactos de Carbon 3 e ajustes residuais.
3. Validacao final de rotas, cache e testes em ambiente com drivers habilitados.
