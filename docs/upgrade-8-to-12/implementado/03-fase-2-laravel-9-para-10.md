# Etapa Implementada: FASE 2 - Upgrade Laravel 9 -> 10

**Data:** Marco/2026
**Fase do Upgrade:** FASE 2 (Laravel 9 -> 10)

---

## O que foi feito

### 1) Atualizacao de dependencias (`composer.json`)

Foram aplicadas as mudancas planejadas para Laravel 10:

- `laravel/framework`: `^9.0` -> `^10.0`
- `phpunit/phpunit`: `^9.5` -> `^10.0`
- `brianium/paratest`: `^6.0` -> `^7.0`
- `barryvdh/laravel-debugbar`: `^3.7` -> `^3.8`
- Removida dependencia direta `phpunit/php-code-coverage` (conflitava com PHPUnit 10)

Ajustes extras para viabilizar o update neste ambiente:

- Adicionado `config.github-protocols = ["https"]`
- Repositorio VCS de `uemanet/eloquent-table` com `no-api: true`
- Adicionado repositorio local do tipo `path` para `packages/uemanet/eloquent-table`
- Dependencia `uemanet/eloquent-table` ajustada para `dev-laravel10`

### 2) Compatibilizacao de pacote customizado (`uemanet/eloquent-table`)

Para destravar o update para Laravel 10, foi criado espelho local temporario do pacote em:

- `packages/uemanet/eloquent-table`

No `composer.json` desse pacote local:

- `version: dev-laravel10`
- `php: >=8.1`
- `illuminate/support`: `^9.0 || ^10.0`
- `illuminate/database`: `^9.0 || ^10.0`
- `illuminate/pagination`: `^9.0 || ^10.0`

### 3) Correcoes obrigatorias da fase no codigo/config

- `app/Http/Kernel.php`
  - Renomeado `protected $routeMiddleware` para `protected $middlewareAliases`.

- `app/Providers/AuthServiceProvider.php`
  - Removido `GateContract` no `boot()`.
  - `boot(GateContract $gate)` -> `boot()`.
  - Removida chamada manual a `registerPolicies($gate)`.

- `app/Providers/EventServiceProvider.php`
  - Removido import legado de `DispatcherContract`.

- `phpunit.xml`
  - Migrado schema para PHPUnit 10.5.
  - Removidos atributos obsoletos (`backupStaticAttributes`, `convert*`, `processUncoveredFiles`).

- `tests/ModulosTestCase.php`
  - Removida sobrescrita de `assertEquals` (metodo final no PHPUnit 10).

### 4) Execucao dos comandos de fase

Executado com sucesso:

- `composer update -W --no-interaction`
- `php artisan optimize:clear`
- `php artisan route:list`

Resultado relevante do Composer:

- Laravel atualizado para `10.50.2`
- PHPUnit atualizado para `10.5.63`
- Paratest atualizado para `7.4.9`
- `uemanet/eloquent-table` passou a ser resolvido via repositorio local (`path`)

---

## Validacao e observacoes

### Testes automatizados

Validacoes executadas:

- `./bin/phpunit --version`
  - `PHPUnit 10.5.63`

- `./bin/phpunit modulos/Seguranca/tests/Repositories/PerfilRepositoryTest.php --stop-on-error`
  - Erro de ambiente: `PDOException: could not find driver`
  - Warning: `No code coverage driver available`

Observacao importante:

- O erro de testes nesta fase continua ligado ao ambiente (driver PDO SQLite ausente), nao a incompatibilidade direta do framework com Laravel 10.

### Estado funcional da aplicacao

- Limpeza de cache e otimizacoes concluidas sem erro.
- Lista de rotas carregada com sucesso no Laravel 10.

---

## Checklist da FASE 2

- [x] Dependencias base do Laravel 10 atualizadas
- [x] `Kernel` ajustado para `middlewareAliases`
- [x] Providers atualizados para assinaturas compativeis
- [x] `phpunit.xml` migrado para PHPUnit 10
- [x] Incompatibilidade de metodo final em testes corrigida
- [x] App sobe e registra rotas no Laravel 10
- [ ] Testes automatizados verdes (bloqueado por ambiente sem PDO SQLite e sem driver de cobertura)

---

## Pendencias para consolidacao

1. Definir estrategia definitiva para `uemanet/eloquent-table`:
   - Publicar branch/tag oficial compativel com Laravel 10 no repositorio remoto, ou
   - Manter pacote local versionado no projeto durante o restante da migracao.

2. Ajustar ambiente de testes:
   - Habilitar `pdo_sqlite` no PHP de CI/dev, ou
   - Migrar configuracao de testes para banco disponivel no ambiente.

---

## Proxima etapa

Iniciar FASE 3 (Laravel 10 -> 11), com foco em:

1. Atualizacao de dependencias para stack Laravel 11.
2. Revisao de middlewares, bootstrap e providers conforme guia oficial.
3. Ajustes de codigo para remocoes/deprecacoes entre 10 e 11.
4. Validacao de testes em ambiente com drivers necessarios habilitados.
