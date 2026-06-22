# Etapa Implementada: FASE 1 - Upgrade Laravel 8 -> 9

**Data:** Marco/2026
**Fase do Upgrade:** FASE 1 (Laravel 8 -> 9)

---

## O que foi feito

### 1) Atualizacao de dependencias (`composer.json`)

Foram aplicadas as mudancas planejadas para Laravel 9:

- `laravel/framework`: `^8.0` -> `^9.0`
- `laravel/ui`: `^3.0` -> `^4.0`
- `laravel/tinker`: `^2.0` -> `^2.7`
- `doctrine/dbal`: `^2.5` -> `^3.0`
- `phpunit/phpunit`: `^9.3` -> `^9.5`
- `symfony/css-selector`: `^4.0` -> `^6.0`
- `symfony/dom-crawler`: `^4.0` -> `^6.0`
- `symfony/console`: `^5.0` -> `^6.0`

Ajustes adicionais necessarios para viabilizar o update:

- `friendsofphp/php-cs-fixer`: `~2.0` -> `^3.0`
- `squizlabs/php_codesniffer`: `~2.6` -> `^3.7`
- `uemanet/eloquent-table`: `dev-master` -> `dev-feat/increase-laravel-version`
- `config.audit.block-insecure = false` (temporario para fase intermediaria)

### 2) Correcoes obrigatorias da fase no codigo/config

- `bootstrap/app.php`
  - Corrigido singleton do ExceptionHandler (removido 3o argumento invalido).

- `config/app.php`
  - Removida chave legada `'log'`.
  - Removido alias legado `'Input'`.

- `config/database.php`
  - Removida configuracao legada `'fetch' => PDO::FETCH_CLASS`.

- `config/mail.php`
  - Migrado para formato de mailers/transport do Laravel 9 (Symfony Mailer).

- `config/logging.php`
  - Criado arquivo de logging no formato atual.

- `app/Exceptions/WhoopsHandler.php`
  - Ajustado tratamento de excecao HTTP para tipagem compativel.
  - Evitado acesso a `getStatusCode()`/`getHeaders()` em excecoes genericas.

- `modulos/Geral/Repositories/AnexoRepository.php`
  - Corrigida compatibilidade com Flysystem 3 (remocao de `getAdapter()`).
  - Recalculado caminho base de uploads a partir da configuracao do disco local.

### 3) Execucao dos comandos de fase

Executado com sucesso:

- `composer update -W --no-interaction`
- `php artisan view:clear`
- `php artisan config:clear`
- `php artisan cache:clear`
- `php artisan route:clear`

Resultado relevante do Composer:

- Laravel atualizado para `v9.52.21`
- `laravelcollective/html` removido do vendor
- Dependencias transicionadas para stack compativel com Laravel 9

---

## Validacao e observacoes

### Testes automatizados

- `php artisan test` nao existe neste projeto (comando indisponivel no Artisan local).
- `./bin/phpunit` foi executado, mas os testes falharam por ambiente:
  - `PDOException: could not find driver` (driver SQLite/PDO ausente no host de execucao).

Conclusao: a validacao automatizada completa depende de ambiente com extensao PDO SQLite habilitada (ou ajuste do banco de testes para MySQL/PostgreSQL).

### Advisory de seguranca no Composer

Apos o update, o Composer reportou 1 advisory pendente em dependencias. Isso era esperado no contexto de fase intermediaria. Tratar no estado final (Laravel 12) com restricao de seguranca reabilitada.

---

## Checklist da FASE 1

- [x] Dependencias base do Laravel 9 atualizadas
- [x] `bootstrap/app.php` corrigido
- [x] `config/mail.php` migrado
- [x] `config/database.php` limpo (`fetch` removido)
- [x] `config/app.php` limpo (`Input` e `log` removidos)
- [x] Cache/views/rotas limpos
- [ ] Testes automatizados verdes (bloqueado por ambiente sem PDO SQLite)

---

## Proxima etapa

Iniciar FASE 2 (Laravel 9 -> 10), com foco em:

1. `app/Http/Kernel.php` (`$routeMiddleware` -> `$middlewareAliases`)
2. Ajustes de providers (`AuthServiceProvider`, `EventServiceProvider`)
3. Preparacao para PHPUnit 10
4. Rodar validacoes em ambiente com driver de banco de testes disponivel
