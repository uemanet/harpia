# Etapa Implementada: FASE 4 - Upgrade Laravel 11 -> 12

**Data:** Marco/2026
**Fase do Upgrade:** FASE 4 (Laravel 11 -> 12)

---

## Correcao aplicada antes da fase (erro reportado em runtime)

Erro reportado ao acessar o sistema:

- `TypeError: in_array(): Argument #2 ($haystack) must be of type array, null given`
- Origem: `modulos/Seguranca/Repositories/ModuloRepository.php`

### Causa raiz

A chave de cache `PERMISSOES_{userId}` pode nao existir em alguns cenarios, retornando `null`.
O metodo `getByUser()` assumia que esse valor era sempre array e passava direto para `in_array()`.

### Correcao aplicada

- `Cache::get('PERMISSOES_' . $userId)` -> `Cache::get('PERMISSOES_' . $userId, [])`
- Sanitizacao defensiva: se o retorno nao for array, converte para `[]`
- `in_array()` com comparacao estrita (`true` no terceiro parametro)
- Ajuste de imports para facades explicitas:
  - `Illuminate\Support\Facades\Cache`
  - `Illuminate\Support\Facades\DB`

### Correcao adicional de log recorrente

Tambem foi removido o mapeamento padrao invalido de policy em `app/Providers/AuthServiceProvider.php`:

- Antes: `'App\\Model' => 'App\\Policies\\ModelPolicy'`
- Depois: `protected $policies = [];`

Isso elimina o erro recorrente de reflection com `App\Policies\ModelPolicy` inexistente.

---

## O que foi feito na FASE 4 (Laravel 11 -> 12)

### 1) Atualizacao de dependencias (`composer.json`)

Alteracoes principais:

- `laravel/framework`: `^11.0` -> `^12.0`
- `phpunit/phpunit`: `^10.0` -> `^11.0`
- `diglactic/laravel-breadcrumbs`: `^9.0` -> `^10.0`
- `laravel/helpers`: `^1.1` -> `^1.6`
- `laravel/ui`: `^4.0` -> `^4.6`
- `spatie/laravel-html`: `^3.5` -> `^3.12`

### 2) Compatibilizacao do pacote customizado local

No pacote `packages/uemanet/eloquent-table/composer.json`, foram ampliadas constraints para Illuminate 12:

- `illuminate/support`: `^9.0 || ^10.0 || ^11.0 || ^12.0`
- `illuminate/database`: `^9.0 || ^10.0 || ^11.0 || ^12.0`
- `illuminate/pagination`: `^9.0 || ^10.0 || ^11.0 || ^12.0`

### 3) Atualizacao do lock

`composer.lock` foi atualizado com sucesso, com destaques:

- `laravel/framework` -> `v12.54.1`
- `phpunit/phpunit` -> `11.5.55`
- `diglactic/laravel-breadcrumbs` -> `v10.0.0`

---

## Execucao dos comandos

Executado com sucesso:

- `composer update -W --no-interaction`
- `php artisan --version`
- `php artisan optimize:clear`
- `php artisan route:list`

Resultado de validacao:

- Versao confirmada: `Laravel Framework 12.54.1`
- Rotas carregadas: `Showing [446] routes`

---

## Testes automatizados (estado atual)

Validacao representativa executada:

- `./bin/phpunit modulos/Seguranca/tests/Repositories/PerfilRepositoryTest.php --stop-on-error`

Resultado:

- Erro de ambiente: `PDOException: could not find driver` (driver de banco para testes ausente)
- Warning: `No code coverage driver available`

Observacao:

- O bloqueio segue ambiental, nao por falha primaria de bootstrap na FASE 4.

---

## Checklist da FASE 4

- [x] Correcao do erro de runtime em `ModuloRepository` (`in_array` com cache nulo)
- [x] Correcao de policy invalida (`ModelPolicy` inexistente)
- [x] Dependencias atualizadas para Laravel 12
- [x] Pacote customizado (`uemanet/eloquent-table`) compatibilizado para Illuminate 12
- [x] Aplicacao sobe e lista rotas no Laravel 12
- [ ] Testes automatizados verdes (bloqueado por ambiente sem PDO SQLite e sem driver de cobertura)

---

## Pendencias finais para consolidacao

1. Ajustar ambiente de testes:
   - habilitar `pdo_sqlite`, ou
   - direcionar testes para conexao de banco disponivel.
2. Revisar e planejar migracao de `laravel/legacy-factories` para class-based factories (melhoria estrutural recomendada para reduzir risco futuro).
3. Rodar bateria completa por modulo apos habilitar infraestrutura de testes.
