# Planejamento Completo — Upgrade Laravel 8 → 12 (Projeto Harpia)

**Documento gerado em:** Março/2026  
**Versão atual do projeto:** Laravel 8.x (`laravel/framework: ^8.0`)  
**Versão alvo:** Laravel 12.x  
**PHP atual exigido:** `^8.1`  
**PHP alvo (Laravel 12):** `^8.2` (recomendado `8.3` ou `8.4`)

---

## Índice

1. [Visão Geral do Projeto](#1-visão-geral-do-projeto)  
2. [Estratégia de Upgrade](#2-estratégia-de-upgrade)  
3. [Pré-requisitos Gerais](#3-pré-requisitos-gerais)  
4. [FASE 1 — Laravel 8 → 9](#4-fase-1--laravel-8--9)  
5. [FASE 2 — Laravel 9 → 10](#5-fase-2--laravel-9--10)  
6. [FASE 3 — Laravel 10 → 11](#6-fase-3--laravel-10--11)  
7. [FASE 4 — Laravel 11 → 12](#7-fase-4--laravel-11--12)  
8. [Impacto nos Módulos](#8-impacto-nos-módulos)  
9. [Pacotes de Terceiros — Compatibilidade](#9-pacotes-de-terceiros--compatibilidade)  
10. [Checklist Final de Validação](#10-checklist-final-de-validação)  
11. [Riscos e Mitigações](#11-riscos-e-mitigações)  
12. [Cronograma Sugerido](#12-cronograma-sugerido)

---

## 1\. Visão Geral do Projeto

O Harpia é um sistema de gestão acadêmica modular construído com Laravel 8\. A aplicação está organizada nos seguintes módulos, localizados em `modulos/`:

| Módulo | Descrição | Models | Controllers | Repositories | Views | Migrations | Tests |
| :---- | :---- | :---- | :---- | :---- | :---- | :---- | :---- |
| **Academico** | Gestão acadêmica (cursos, matrículas, disciplinas, etc.) | 30 | 35+ | 31 | ✅ | 43+ | ✅ |
| **Alunos** | Portal do aluno | 1 | — | — | ✅ | — | ✅ |
| **Core** | Classes base (BaseModel, BaseController, BaseRequest, BaseRepository) | 1 | 1 | 1 | — | — | — |
| **Geral** | Dados gerais (pessoas, documentos, titulações) | 7 | 6 | — | ✅ | — | ✅ |
| **Integracao** | Integração com ambientes virtuais (Moodle) | 6 | 5 | — | ✅ | — | ✅ |
| **Monitoramento** | Monitoramento do sistema | 0 | — | — | ✅ | — | ✅ |
| **RH** | Recursos humanos (colaboradores, funções, horas, etc.) | 21 | 10+ | 15+ | ✅ | 15+ | ✅ |
| **Seguranca** | Autenticação, autorização, auditoria, menus | 6 | 10+ | 5 | ✅ | 11 | ✅ |

### Componentes personalizados em `Harpia/`:

- `Configuracao` — Provider de configuração global  
- `Event` — Eventos base  
- `FlashToastrAlert` — Alertas toast  
- `Format` — Formatação de dados  
- `Matriz` — Lógica de matrizes curriculares  
- `Menu` — Lógica de menus  
- `Moodle` — Integração com Moodle  
- `Tree` — Estrutura de árvore  
- `Util` — Utilitários gerais  
- `Validator` — Validações customizadas

### Principais Dependências Externas:

- `laravelcollective/html: ^6.2` — **ABANDONADO, crítico**  
- `laravel/legacy-factories: ^1.3` — **Precisa ser migrado**  
- `laravel/helpers: ^1.1` — Helpers globais (string/array)  
- `laravel/ui: ^3.0` — Autenticação UI  
- `maatwebsite/excel: ^3.1` — Exportação Excel  
- `mpdf/mpdf: ^8.0` — Geração de PDF  
- `doctrine/dbal: ^2.5` — Removido no Laravel 11  
- `diglactic/laravel-breadcrumbs: ^9.0` — Breadcrumbs  
- `spatie/laravel-html: ^3.5` — HTML builder  
- `uemanet/eloquent-table: dev-master` — Tabelas Eloquent (pacote custom)  
- `filp/whoops: ^2.0` — Manipulação de erros

---

## 2\. Estratégia de Upgrade

O upgrade será feito **de forma incremental**, versão por versão:

Laravel 8 → 9 → 10 → 11 → 12

**Justificativa:** Cada versão major tem breaking changes específicas. Pular versões aumenta o risco de incompatibilidades silenciosas. A abordagem incremental permite:

- Testar a cada passo  
- Isolar problemas  
- Manter o sistema funcional entre fases

**Recomendação:** Criar uma branch dedicada (`upgrade/laravel-12`) e fazer commits após cada fase bem-sucedida.

---

## 3\. Pré-requisitos Gerais

### 3.1. Ambiente

- [ ] Atualizar PHP para `>= 8.2` (recomendado 8.3 ou 8.4)  
- [ ] Atualizar Composer para `>= 2.2.0`  
- [ ] Atualizar curl para `>= 7.34.0`  
- [ ] Atualizar Docker: trocar imagem `ambientum/php:7.1-apache` por imagem com PHP 8.2+  
- [ ] Atualizar MySQL no Docker de `5.7` para `8.0+`

### 3.2. Controle de Versão

- [ ] Criar branch: `git checkout -b upgrade/laravel-12`  
- [ ] Garantir que todos os testes passam na versão atual antes de começar  
- [ ] Fazer backup do banco de dados

### 3.3. Ferramentas Úteis

- [Laravel Shift](https://laravelshift.com/) — automatiza upgrades (pago, mas eficiente)  
- [Rector](https://github.com/rectorphp/rector) — refatoração automática de código PHP  
- `composer outdated` — verificar pacotes desatualizados

---

## 4\. FASE 1 — Laravel 8 → 9

**Referência:** [https://laravel.com/docs/9.x/upgrade](https://laravel.com/docs/9.x/upgrade)  
**Tempo estimado:** 2-4 horas  
**PHP mínimo:** 8.0.2

### 4.1. Atualizar Dependências no `composer.json`

{

  "require": {

    "php": "^8.0.2",

    "laravel/framework": "^9.0",

    "laravel/ui": "^4.0",

    "laravel/tinker": "^2.7",

    "doctrine/dbal": "^3.0"

  },

  "require-dev": {

    "phpunit/phpunit": "^9.5",

    "mockery/mockery": "^1.6",

    "fakerphp/faker": "^1.23",

    "symfony/css-selector": "^6.0",

    "symfony/dom-crawler": "^6.0",

    "symfony/console": "^6.0",

    "brianium/paratest": "^6.0",

    "barryvdh/laravel-debugbar": "^3.7"

  }

}

### 4.2. Alterações Obrigatórias

#### 4.2.1. PHP Return Types

Os seguintes métodos precisam de return types se estiverem sobrescritos:

- `offsetGet($key): mixed`  
- `offsetSet($key, $value): void`  
- `offsetExists($key): bool`  
- `offsetUnset($key): void`

**Arquivos a verificar:**

- `modulos/Core/Model/BaseModel.php`  
- `modulos/Core/Repository/BaseRepository.php`  
- Todos os models que sobrescrevam esses métodos

#### 4.2.2. `bootstrap/app.php` — Correção do Singleton do ExceptionHandler

O arquivo atual tem um BUG — 3 argumentos no singleton:

// ANTES (bugado):

$app-\>singleton(

    Illuminate\\Contracts\\Debug\\ExceptionHandler::class,

    App\\Exceptions\\WhoopsHandler::class,

    App\\Exceptions\\Handler::class  // \<- 3º argumento inválido

);

// DEPOIS:

$app-\>singleton(

    Illuminate\\Contracts\\Debug\\ExceptionHandler::class,

    App\\Exceptions\\WhoopsHandler::class

);

#### 4.2.3. Flysystem 3.x

O Laravel 9 usa Flysystem 3.x. Se houver uso direto de APIs do Flysystem (ex: `Storage::getDriver()`), será necessário atualizar.

**Verificar:** Uso de `Storage::` nos módulos.

#### 4.2.4. Symfony Mailer

O Laravel 9 substitui SwiftMailer por Symfony Mailer.

**Arquivo afetado:** `config/mail.php`

- Renomear `'driver'` para `'transport'`  
- Renomear `'smtp'` configuration keys conforme necessário  
- Remover opções não suportadas (`stream`, etc.)

// ANTES:

'driver' \=\> env('MAIL\_DRIVER', 'smtp'),

// DEPOIS:

'mailer' \=\> env('MAIL\_MAILER', 'smtp'),

// (Novo formato com array 'mailers')

**Recomendação:** Substituir todo o `config/mail.php` pelo template do Laravel 9\.

#### 4.2.5. `config/database.php` — Remover `fetch`

A opção `'fetch' => PDO::FETCH_CLASS` foi removida no Laravel 5.4+ (já deveria ter sido removida antes):

// REMOVER esta linha:

'fetch' \=\> PDO::FETCH\_CLASS,

#### 4.2.6. Postgres Schema Configuration

Se usar PostgreSQL, a config `schema` agora aceita array:

'pgsql' \=\> \[

    // ...

    'search\_path' \=\> 'public',  // renomear de 'schema'

\],

#### 4.2.7. `config/app.php` — Remover `'log'`

// REMOVER esta linha (usar logging.php no lugar):

'log' \=\> env('APP\_LOG', 'single'),

Se não existir `config/logging.php`, criar um baseado no template do Laravel 9\.

### 4.3. Alterações nos Módulos

#### Não há mudanças específicas obrigatórias nos módulos para 8→9.

A maior parte das mudanças é transparente. Porém, validar:

- Qualquer uso de `Illuminate\Http\Exception\HttpResponseException` (encontrado em `app/Exceptions/WhoopsHandler.php`) — substituir por `Illuminate\Http\Exceptions\HttpResponseException` (com "s")  
- Testar todos os módulos após o upgrade

### 4.4. Executar

composer update

php artisan view:clear

php artisan config:clear

php artisan cache:clear

php artisan route:clear

php artisan test

---

## 5\. FASE 2 — Laravel 9 → 10

**Referência:** [https://laravel.com/docs/10.x/upgrade](https://laravel.com/docs/10.x/upgrade)  
**Tempo estimado:** 1-2 horas  
**PHP mínimo:** 8.1.0

### 5.1. Atualizar Dependências no `composer.json`

{

  "require": {

    "php": "^8.1",

    "laravel/framework": "^10.0",

    "laravel/ui": "^4.0",

    "doctrine/dbal": "^3.0"

  },

  "require-dev": {

    "phpunit/phpunit": "^10.0",

    "brianium/paratest": "^7.0",

    "barryvdh/laravel-debugbar": "^3.8"

  }

}

### 5.2. Alterações Obrigatórias

#### 5.2.1. Propriedade `$dates` dos Models

A propriedade `$dates` foi depreciada em favor de `$casts`.

**Impacto no Harpia:** Nenhum dos models usa `$dates`, então **não há ação necessária**.

#### 5.2.2. `$routeMiddleware` → `$middlewareAliases`

No `app/Http/Kernel.php`, renomear (opcional, mas recomendado):

// ANTES:

protected $routeMiddleware \= \[

    'auth' \=\> \\App\\Http\\Middleware\\Authenticate::class,

    // ...

\];

// DEPOIS:

protected $middlewareAliases \= \[

    'auth' \=\> \\App\\Http\\Middleware\\Authenticate::class,

    // ...

\];

#### 5.2.3. `AuthServiceProvider` — Remover `registerPolicies()`

O método `registerPolicies()` agora é chamado automaticamente pelo framework:

// ANTES (app/Providers/AuthServiceProvider.php):

public function boot(GateContract $gate)

{

    $this-\>registerPolicies($gate);

}

// DEPOIS:

public function boot()

{

    // registerPolicies é chamado automaticamente

}

**Nota:** Também remover o parâmetro `GateContract $gate` e o import.

#### 5.2.4. `EventServiceProvider` — Atualizar assinatura `boot()`

// ANTES (app/Providers/EventServiceProvider.php):

use Illuminate\\Contracts\\Events\\Dispatcher as DispatcherContract;

// DEPOIS: Remover import desnecessário, manter boot() simples

public function boot()

{

    parent::boot();

}

#### 5.2.5. Database Expressions

Se houver uso de `DB::raw()` com comparações diretas no resultado (ex: `(string) DB::raw(...)`), pode quebrar. Verificar repositories.

#### 5.2.6. PHPUnit 10

Se atualizar para PHPUnit 10:

- Remover atributo `processUncoveredFiles` do `phpunit.xml`  
- Remover `convertErrorsToExceptions`, `convertNoticesToExceptions`, `convertWarningsToExceptions`  
- Remover `backupStaticAttributes`

\<\!-- ANTES: \--\>

\<phpunit backupGlobals="false" backupStaticAttributes="false" bootstrap="bootstrap/autoload.php"

  colors="true" convertErrorsToExceptions="true" convertNoticesToExceptions="true"

  convertWarningsToExceptions="true" processIsolation="false" stopOnFailure="false"\>

  \<coverage processUncoveredFiles="true"\>

\<\!-- DEPOIS: \--\>

\<phpunit backupGlobals="false" bootstrap="bootstrap/autoload.php"

  colors="true" processIsolation="false" stopOnFailure="false"\>

  \<coverage\>

### 5.3. Executar

composer update

php artisan optimize:clear

php artisan test

---

## 6\. FASE 3 — Laravel 10 → 11

**Referência:** [https://laravel.com/docs/11.x/upgrade](https://laravel.com/docs/11.x/upgrade)  
**Tempo estimado:** 4-8 horas (MAIOR IMPACTO)  
**PHP mínimo:** 8.2.0

### ⚠️ ATENÇÃO: Esta é a fase mais complexa.

O Laravel 11 introduziu uma nova estrutura de aplicação, mas **suporta a estrutura do Laravel 10**. Não é necessário migrar para a nova estrutura. O projeto Harpia pode manter seus arquivos atuais.

### 6.1. Atualizar Dependências no `composer.json`

{

  "require": {

    "php": "^8.2",

    "laravel/framework": "^11.0",

    "laravel/ui": "^4.0",

    "laravel/tinker": "^2.9"

  },

  "require-dev": {

    "phpunit/phpunit": "^10.5 || ^11.0",

    "fakerphp/faker": "^1.23",

    "mockery/mockery": "^1.6",

    "brianium/paratest": "^7.0",

    "barryvdh/laravel-debugbar": "^3.10"

  }

}

### 6.2. Alterações Obrigatórias

#### 6.2.1. ⚠️ Remover `doctrine/dbal`

O Laravel 11 removeu a dependência do `doctrine/dbal`:

// REMOVER do composer.json:

"doctrine/dbal": "^3.0"

Se houver migrations que usam `$table->change()` para modificar colunas, o Laravel 11 lida com isso nativamente. **Verificar todas as migrations dos módulos.**

#### 6.2.2. ⚠️ Remover `laravel/legacy-factories`

O pacote `laravel/legacy-factories` **não é compatível com Laravel 11**. Todas as factories precisam ser migradas para o formato de classe (class-based factories).

**Arquivo atual:** `database/factories/ModelFactory.php` (632 linhas, \~30 factories usando `$factory->define()`)

**Ação necessária:** Criar um arquivo de factory por model, usando a nova sintaxe:

// ANTES (database/factories/ModelFactory.php):

$factory-\>define(Modulos\\Geral\\Models\\Pessoa::class, function (Faker\\Generator $faker) {

    return \['pes\_nome' \=\> $faker-\>name, ...\];

});

// DEPOIS (database/factories/PessoaFactory.php):

namespace Database\\Factories\\Modulos\\Geral\\Models;

use Illuminate\\Database\\Eloquent\\Factories\\Factory;

use Modulos\\Geral\\Models\\Pessoa;

class PessoaFactory extends Factory

{

    protected $model \= Pessoa::class;

    public function definition(): array

    {

        return \['pes\_nome' \=\> $this-\>faker-\>name, ...\];

    }

}

**Atenção:** Os models precisam usar o trait `HasFactory`:

use Illuminate\\Database\\Eloquent\\Factories\\HasFactory;

class Pessoa extends BaseModel

{

    use HasFactory;

    // ...

}

**E atualizar todos os testes que usam `factory()` helper:**

// ANTES:

factory(Modulos\\Geral\\Models\\Pessoa::class)-\>create();

// DEPOIS:

Modulos\\Geral\\Models\\Pessoa::factory()-\>create();

**Lista de factories a migrar (de `database/factories/ModelFactory.php`):**

1. `Modulos\Geral\Models\Pessoa`  
2. `Modulos\Geral\Models\TipoDocumento`  
3. `Modulos\Geral\Models\Documento`  
4. `Modulos\Geral\Models\Anexo`  
5. `Modulos\Geral\Models\Configuracao`  
6. `Modulos\Geral\Models\Titulacao`  
7. `Modulos\Geral\Models\TitulacaoInformacao`  
8. `Modulos\Seguranca\Models\Modulo`  
9. `Modulos\Seguranca\Models\Perfil`  
10. `Modulos\Seguranca\Models\Permissao`  
11. `Modulos\Seguranca\Models\Usuario`  
12. `Modulos\Seguranca\Models\MenuItem`  
13. `Modulos\Seguranca\Models\Auditoria`  
14. `Modulos\Academico\Models\Departamento`  
15. `Modulos\Academico\Models\Centro`  
16. `Modulos\Academico\Models\Professor`  
17. `Modulos\Academico\Models\PeriodoLetivo`  
18. `Modulos\Academico\Models\Polo`  
19. `Modulos\Academico\Models\Curso`  
20. `Modulos\Academico\Models\ListaSemtur`  
21. *(e possivelmente mais — verificar todo o arquivo de 632 linhas)*

**Testes afetados (usam `factory()` global):**

- `modulos/Seguranca/tests/` — todos os testes  
- `modulos/Integracao/tests/` — todos os testes  
- `modulos/Academico/tests/` — todos os testes  
- `modulos/Geral/tests/` — todos os testes  
- `modulos/RH/tests/` — todos os testes

#### 6.2.3. Password Rehashing

O model `Usuario` usa um campo de senha customizado (`usr_senha`). É necessário definir:

class Usuario extends BaseModel implements AuthenticatableContract, ...

{

    // Adicionar:

    protected $authPasswordName \= 'usr\_senha';

}

#### 6.2.4. `CheckForMaintenanceMode` → `PreventRequestsDuringMaintenance`

No `app/Http/Kernel.php`:

// ANTES:

\\Illuminate\\Foundation\\Http\\Middleware\\CheckForMaintenanceMode::class,

// DEPOIS:

\\Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance::class,

**Nota:** `CheckForMaintenanceMode` foi removido no Laravel 11\.

#### 6.2.5. Migrations — Named classes vs Anonymous classes

As migrations com classes nomeadas continuam funcionando, mas o Laravel 11 prefere classes anônimas. **Não é obrigatório mudar**, mas recomendado para novas migrations.

#### 6.2.6. Spatie Once Package

Se houver uso do pacote `spatie/once`, ele agora é incompatível. O Laravel 11 tem sua própria implementação de `once()`.

#### 6.2.7. Criar `config/logging.php`

Se ainda não existir, criar este arquivo de configuração (necessário desde o Laravel 5.6 mas agora obrigatório):

\<?php

return \[

    'default' \=\> env('LOG\_CHANNEL', 'stack'),

    'channels' \=\> \[

        'stack' \=\> \[

            'driver' \=\> 'stack',

            'channels' \=\> \['single'\],

        \],

        'single' \=\> \[

            'driver' \=\> 'single',

            'path' \=\> storage\_path('logs/laravel.log'),

            'level' \=\> env('LOG\_LEVEL', 'debug'),

        \],

        'daily' \=\> \[

            'driver' \=\> 'daily',

            'path' \=\> storage\_path('logs/laravel.log'),

            'level' \=\> env('LOG\_LEVEL', 'debug'),

            'days' \=\> 14,

        \],

    \],

\];

### 6.3. Executar

composer update

php artisan optimize:clear

php artisan test

---

## 7\. FASE 4 — Laravel 11 → 12

**Referência:** [https://laravel.com/docs/12.x/upgrade](https://laravel.com/docs/12.x/upgrade)  
**Tempo estimado:** 1-2 horas  
**PHP mínimo:** 8.2.0

### 7.1. Atualizar Dependências no `composer.json`

{

  "require": {

    "php": "^8.2",

    "laravel/framework": "^12.0"

  },

  "require-dev": {

    "phpunit/phpunit": "^11.0"

  }

}

### 7.2. Alterações Obrigatórias

#### 7.2.1. Carbon 3

O Laravel 12 exige Carbon 3.x (suporte ao Carbon 2.x foi removido).

**Verificar:** Uso de `Carbon\Carbon` nos models (ex: `Modulos\Academico\Models\Curso`, `Modulos\Geral\Models\Pessoa`).

Principais breaking changes do Carbon 3:

- `diffInDays()` agora retorna float em vez de int  
- Alguns métodos depreciados foram removidos  
- Verificar todos os usos de Carbon nos repositories e controllers

#### 7.2.2. Image Validation

Regra `image` agora exclui SVGs. Se houver uploads de imagem que aceitam SVG, usar `extensions:svg` explicitamente.

#### 7.2.3. Container Class Dependency Resolution

O container agora respeita valores default de propriedades ao resolver dependências. Verificar se há construtores com defaults `null` que dependiam de auto-resolução.

#### 7.2.4. Nested Array Request Merging

O método `$request->merge()` agora faz merge recursivo em arrays aninhados.

### 7.3. Executar

composer update

php artisan optimize:clear

php artisan test

---

## 8\. Impacto nos Módulos

### 8.1. Módulo Core (`modulos/Core/`)

| Arquivo | Mudança Necessária | Fase |
| :---- | :---- | :---- |
| `Model/BaseModel.php` | Adicionar trait `HasFactory`; verificar `boot()` com `parent::observe()` | 11 |
| `Http/Controller/BaseController.php` | `DispatchesJobs` pode ser removido (já incluído em `Controller` base) | 9 |
| `Http/Request/BaseRequest.php` | O método `response()` foi removido do FormRequest no Laravel 8+. Verificar compatibilidade | 9 |
| `Repository/BaseRepository.php` | Sem mudanças obrigatórias | — |

### 8.2. Módulo Academico (`modulos/Academico/`)

| Componente | Quantidade | Impacto |
| :---- | :---- | :---- |
| Models | 30 | Adicionar `HasFactory` em todos (Fase 11\) |
| Controllers | 35+ | Nenhuma mudança estrutural obrigatória |
| Repositories | 31 | Verificar uso de `DB` facade alias (usar import completo) |
| Migrations | 43+ | Classes nomeadas funcionam, sem mudança obrigatória |
| Views (Blade) | 30+ | Verificar uso de `Form::` (LaravelCollective) |
| Tests | Vários | Migrar `factory()` helper para `Model::factory()` |

**Arquivos específicos a atentar:**

- `Http/Controllers/CursosController.php` — usa `use Auth;` e `use DB;` (alias globais)  
- `Http/Middleware/Vinculo.php` — usa `use Auth;`  
- `Repositories/AlunoRepository.php` — usa `use Auth;`  
- `Repositories/CursoRepository.php` — usa `use Auth;`  
- Vários Repositories — usam `use DB;`

### 8.3. Módulo Seguranca (`modulos/Seguranca/`)

| Componente | Mudança | Fase |
| :---- | :---- | :---- |
| `Models/Usuario.php` | Adicionar `$authPasswordName = 'usr_senha'` | 11 |
| `Observers/AuditoriaObserver.php` | Substituir `use Auth;` e `use DB;` por imports completos | 9+ |
| `Providers/Seguranca/SegurancaMiddleware.php` | Verificar compatibilidade | 11 |
| `Http/Controllers/Auth/AuthController.php` | Usa `AuthenticatesUsers` trait — verificar compatibilidade | 9+ |
| `Http/Controllers/Auth/PasswordController.php` | Usa `use DB;` | 9+ |
| Tests | Migrar `factory()` para class-based | 11 |

### 8.4. Módulo Geral (`modulos/Geral/`)

| Componente | Mudança | Fase |
| :---- | :---- | :---- |
| Models (7) | Adicionar `HasFactory` | 11 |
| `Repositories/PessoaRepository.php` | `use DB;` → import completo | 9+ |
| `Http/Middlewares/VerificaPessoaMiddleware.php` | Verificar assinatura | 10+ |

### 8.5. Módulo RH (`modulos/RH/`)

| Componente | Mudança | Fase |
| :---- | :---- | :---- |
| Models (21) | Adicionar `HasFactory`; `TipoJustificativa` extends `Model` diretamente — verificar | 11 |
| Controllers | `use DB;` em HorasTrabalhadasDiariasController | 9+ |
| Repositories | Vários usam `use DB;` | 9+ |
| Views | Usa `Form::` (LaravelCollective) extensivamente | — |

### 8.6. Módulo Integracao (`modulos/Integracao/`)

| Componente | Mudança | Fase |
| :---- | :---- | :---- |
| Models (6) | Adicionar `HasFactory` | 11 |
| Repositories | `use DB;` em vários | 9+ |
| Tests | Migrar `factory()` | 11 |

### 8.7. Módulo Monitoramento (`modulos/Monitoramento/`)

- Impacto mínimo — sem models com dados

### 8.8. Módulo Alunos (`modulos/Alunos/`)

- 1 model (`ComprovanteMatricula`) — adicionar `HasFactory`

---

## 9\. Pacotes de Terceiros — Compatibilidade

### 9.1. ⚠️ CRÍTICO: `laravelcollective/html: ^6.2`

**Status:** ABANDONADO E INCOMPATÍVEL com Laravel 9+.

O LaravelCollective foi descontinuado. O projeto usa extensivamente `Form::` nas views Blade de **todos os módulos**.

**Opções:**

1. **Substituir por `spatie/laravel-html`** (já instalado no projeto como `^3.5`) — Sintaxe diferente  
2. **Substituir por HTML puro** em todos os formulários Blade  
3. **Usar `laravels/html`** (fork comunitário) — verificar compatibilidade

**Estimativa de impacto:** \~50+ arquivos Blade usam `Form::open()`, `Form::model()`, `Form::text()`, `Form::select()`, `Form::submit()`, `Form::close()`, etc.

**Recomendação:** Substituir por HTML puro \+ diretivas Blade (`@csrf`, `@method`). Exemplo:

{{-- ANTES: \--}}

{\!\! Form::open(\["route" \=\> 'seguranca.usuarios.create', "method" \=\> "POST"\]) \!\!}

{\!\! Form::text('usr\_usuario', old('usr\_usuario'), \['class' \=\> 'form-control'\]) \!\!}

{\!\! Form::submit('Salvar', \['class' \=\> 'btn btn-primary'\]) \!\!}

{\!\! Form::close() \!\!}

{{-- DEPOIS: \--}}

\<form action="{{ route('seguranca.usuarios.create') }}" method="POST"\>

    @csrf

    \<input type="text" name="usr\_usuario" value="{{ old('usr\_usuario') }}" class="form-control"\>

    \<button type="submit" class="btn btn-primary"\>Salvar\</button\>

\</form\>

**Fazer na Fase 1 (8→9)**, pois o pacote não suporta Laravel 9\.

### 9.2. ⚠️ CRÍTICO: `laravel/legacy-factories: ^1.3`

**Status:** INCOMPATÍVEL com Laravel 11+.

**Ação:** Migrar para class-based factories (ver seção 6.2.2).

**Fazer na Fase 3 (10→11).**

### 9.3. `laravel/helpers: ^1.1`

Fornece helpers globais como `str_*`, `array_*`, `camel_case()`, etc.

**Verificar compatibilidade:** Versão `^1.5` suporta Laravel 9-11. Versão `^1.6` suporta Laravel 12\.

**Ação:** Atualizar versão a cada fase.

### 9.4. `laravel/ui: ^3.0`

**Ação:**

- Laravel 9: `^4.0`  
- Laravel 10-11: `^4.0`  
- Laravel 12: `^4.6`

### 9.5. `maatwebsite/excel: ^3.1`

**Compatibilidade:**

- Laravel 9: `^3.1`  
- Laravel 10: `^3.1`  
- Laravel 11: `^3.1` (verificar)  
- Laravel 12: Verificar releases mais recentes

### 9.6. `uemanet/eloquent-table: dev-master`

**Status:** Pacote customizado do repositório da UEMANET.

**Risco:** Pode não ser compatível com versões mais recentes do Laravel. Verificar o repositório e testar.

**Ação:** Se não for compatível, considerar um fork e atualização.

### 9.7. `diglactic/laravel-breadcrumbs: ^9.0`

**Compatibilidade:** Verificar versão compatível para cada Laravel.

### 9.8. `spatie/laravel-html: ^3.5`

**Compatibilidade:** `^3.5` pode não suportar Laravel 12\. Verificar `^3.12+`.

### 9.9. `filp/whoops: ^2.0`

**Compatibilidade:**

- Laravel 9+: `^2.14`  
- Manter atualizado

### 9.10. `mpdf/mpdf: ^8.0`

**Compatibilidade:** Funciona com PHP 8.2+ — sem problemas esperados.

### 9.11. `squizlabs/php_codesniffer: ~2.6` e `friendsofphp/php-cs-fixer: ~2.0`

**Ação:** Atualizar para versões modernas:

- `squizlabs/php_codesniffer: ^3.7`  
- `friendsofphp/php-cs-fixer: ^3.0`

### 9.12. `Illuminate\Support\Facades\Input`

**Localização:** `config/app.php` (alias) **Status:** Removido desde Laravel 6.x. **Remover imediatamente.**

// REMOVER do aliases em config/app.php:

'Input' \=\> Illuminate\\Support\\Facades\\Input::class,

Substituir qualquer uso de `Input::` por `Request::input()` ou `request()`.

---

## 10\. Checklist Final de Validação

### Após cada fase:

- [ ] `composer update` executa sem erros  
- [ ] `php artisan optimize:clear` executa  
- [ ] `php artisan migrate --pretend` funciona  
- [ ] `php artisan route:list` mostra todas as rotas  
- [ ] `php artisan test` — todos os testes passam  
- [ ] Navegação manual nos módulos principais  
- [ ] Login/logout funciona  
- [ ] CRUD de pelo menos 1 entidade por módulo funciona  
- [ ] Exportação Excel funciona  
- [ ] Geração de PDF funciona  
- [ ] Integração Moodle funciona (se configurada)

### Validação final (após Laravel 12):

- [ ] PHP `>= 8.2` em produção  
- [ ] Todas as factories migradas para class-based  
- [ ] `laravelcollective/html` completamente removido  
- [ ] `laravel/legacy-factories` removido  
- [ ] `doctrine/dbal` removido  
- [ ] `Input` facade removido  
- [ ] `config/logging.php` configurado  
- [ ] `config/mail.php` no novo formato  
- [ ] `config/database.php` limpo (sem `fetch`, sem `schema` antigo)  
- [ ] `phpunit.xml` atualizado para PHPUnit 11  
- [ ] Docker atualizado com PHP 8.2+ e MySQL 8.0+  
- [ ] Todos os testes passando  
- [ ] Deploy em staging realizado com sucesso

---

## 11\. Riscos e Mitigações

| \# | Risco | Impacto | Probabilidade | Mitigação |
| :---- | :---- | :---- | :---- | :---- |
| 1 | `laravelcollective/html` incompatível | Alto | Certo | Substituir por HTML puro em 50+ views |
| 2 | `laravel/legacy-factories` incompatível | Alto | Certo | Migrar 20+ factories para class-based |
| 3 | `uemanet/eloquent-table` incompatível | Alto | Média | Fork e atualizar; ou substituir por alternativa |
| 4 | Testes quebrando após migrar factories | Médio | Alta | Migrar cuidadosamente, testar cada módulo |
| 5 | Aliases de Facade globais (`use DB;`, `use Auth;`, `use Route;`) | Baixo | Baixa | Substituir por imports completos gradualmente |
| 6 | Middleware `CheckForMaintenanceMode` removido | Médio | Certo | Substituir por `PreventRequestsDuringMaintenance` |
| 7 | `BaseRequest::response()` custom incompatível | Médio | Média | Atualizar método para nova assinatura do FormRequest |
| 8 | Carbon 3 breaking changes | Médio | Média | Testar todos os cálculos de data |
| 9 | `WhoopsHandler` com import errado | Baixo | Certo | Corrigir namespace do HttpResponseException |
| 10 | Configurações antigas em `config/*.php` | Baixo | Certo | Substituir por templates atualizados |

---

## 12\. Cronograma Sugerido

| Fase | Atividade | Duração Estimada | Dependências |
| :---- | :---- | :---- | :---- |
| **0** | Preparação (ambiente, backup, branch) | 1 dia | — |
| **1** | Laravel 8 → 9 | 3-5 dias | Remoção do LaravelCollective (50+ views) |
| **1.1** | Substituir `Form::` por HTML puro em todas as views | 2-3 dias | Fase 1 |
| **1.2** | Testes e validação | 1 dia | Fase 1.1 |
| **2** | Laravel 9 → 10 | 1-2 dias | Fase 1 completa |
| **2.1** | Testes e validação | 0.5 dia | Fase 2 |
| **3** | Laravel 10 → 11 | 5-8 dias | Migração de factories (MAIOR ESFORÇO) |
| **3.1** | Migrar ModelFactory.php para class-based factories | 2-3 dias | Fase 3 |
| **3.2** | Atualizar todos os testes (`factory()` → `Model::factory()`) | 2-3 dias | Fase 3.1 |
| **3.3** | Testes e validação | 1 dia | Fase 3.2 |
| **4** | Laravel 11 → 12 | 1-2 dias | Fase 3 completa |
| **4.1** | Testes e validação final | 1 dia | Fase 4 |
| **5** | Deploy em staging | 1 dia | Todas as fases |
| **6** | Deploy em produção | 1 dia | Aprovação em staging |
|  | **TOTAL ESTIMADO** | **15-25 dias úteis** |  |

---

## Apêndice A — Resumo de Alterações no `composer.json` (Estado Final)

{

    "name": "uemanet/harpia",

    "description": "Sistema de gestão modular.",

    "keywords": \["php", "harpia", "laravel"\],

    "license": "MIT",

    "type": "project",

    "repositories": \[

        {

            "type": "vcs",

            "url": "https://github.com/uemanet/eloquent-table.git"

        }

    \],

    "require": {

        "php": "^8.2",

        "laravel/framework": "^12.0",

        "guzzlehttp/guzzle": "^7.0.1",

        "mpdf/mpdf": "^8.0",

        "ramsey/uuid": "^4.2",

        "maatwebsite/excel": "^3.1",

        "laravel/tinker": "^2.9",

        "uemanet/eloquent-table": "dev-master",

        "laravel/helpers": "^1.6",

        "laravel/ui": "^4.6",

        "spatie/laravel-html": "^3.12",

        "diglactic/laravel-breadcrumbs": "^9.0"

    },

    "require-dev": {

        "fakerphp/faker": "^1.23",

        "filp/whoops": "^2.14",

        "mockery/mockery": "^1.6",

        "phpunit/phpunit": "^11.0",

        "squizlabs/php\_codesniffer": "^3.7",

        "friendsofphp/php-cs-fixer": "^3.0",

        "barryvdh/laravel-debugbar": "^3.10"

    }

}

**Pacotes REMOVIDOS:**

- `laravelcollective/html` — Substituído por HTML puro  
- `laravel/legacy-factories` — Migrado para class-based factories  
- `doctrine/dbal` — Não necessário no Laravel 11+  
- `symfony/css-selector` — Avaliar necessidade  
- `symfony/dom-crawler` — Avaliar necessidade  
- `symfony/console` — Já é dependência transitiva do framework  
- `brianium/paratest` — Avaliar necessidade com PHPUnit 11  
- `phpunit/php-code-coverage` — Já incluído no PHPUnit

---

## Apêndice B — Arquivos-Chave a Modificar

| Arquivo | Fases Afetadas | Descrição da Mudança |
| :---- | :---- | :---- |
| `composer.json` | 1, 2, 3, 4 | Atualizar dependências a cada fase |
| `bootstrap/app.php` | 1 | Corrigir singleton do ExceptionHandler (3 args → 2\) |
| `config/app.php` | 1 | Remover alias `Input`, remover `'log'`, atualizar providers |
| `config/database.php` | 1 | Remover `'fetch' => PDO::FETCH_CLASS`, atualizar pgsql `search_path` |
| `config/mail.php` | 1 | Migrar para formato Symfony Mailer |
| `config/logging.php` | 1 | CRIAR arquivo (não existe) |
| `app/Http/Kernel.php` | 2, 3 | `$routeMiddleware` → `$middlewareAliases`; `CheckForMaintenanceMode` → `PreventRequestsDuringMaintenance` |
| `app/Providers/AuthServiceProvider.php` | 2 | Remover `registerPolicies()` call e `GateContract` |
| `app/Providers/EventServiceProvider.php` | 2 | Remover `DispatcherContract` import |
| `app/Exceptions/WhoopsHandler.php` | 1 | Corrigir import `HttpResponseException` |
| `modulos/Core/Model/BaseModel.php` | 3 | Adicionar `HasFactory` trait |
| `modulos/Seguranca/Models/Usuario.php` | 3 | Adicionar `$authPasswordName` |
| `database/factories/ModelFactory.php` | 3 | SUBSTITUIR por class-based factories |
| `phpunit.xml` | 2, 4 | Remover atributos depreciados |
| `docker-compose.yml` | 0 | Atualizar imagem PHP e MySQL |
| Todos os `modulos/*/Models/*.php` | 3 | Adicionar `HasFactory` trait |
| Todos os `modulos/*/tests/**/*.php` | 3 | Migrar `factory()` → `Model::factory()` |
| Todos os `modulos/*/Views/**/*.blade.php` com `Form::` | 1 | Substituir LaravelCollective por HTML puro |

---

## Apêndice C — Comandos Úteis

\# Limpar caches após cada fase

php artisan optimize:clear

\# Verificar rotas

php artisan route:list

\# Rodar testes

php artisan test

\# Verificar pacotes desatualizados

composer outdated

\# Verificar compatibilidade de pacotes

composer why-not laravel/framework 12.0

\# Recriar autoload

composer dump-autoload

\# Verificar versão do Laravel

php artisan \--version

---

## Apêndice D — Referências

- [Laravel 9.x Upgrade Guide](https://laravel.com/docs/9.x/upgrade)  
- [Laravel 10.x Upgrade Guide](https://laravel.com/docs/10.x/upgrade)  
- [Laravel 11.x Upgrade Guide](https://laravel.com/docs/11.x/upgrade)  
- [Laravel 12.x Upgrade Guide](https://laravel.com/docs/12.x/upgrade)  
- [Carbon 3 Migration Guide](https://carbon.nesbot.com/guide/getting-started/migration.html)  
- [LaravelCollective EOL](https://github.com/LaravelCollective/html)  
- [Class-based Factories Migration](https://laravel.com/docs/11.x/eloquent-factories)  
- [PHPUnit 11 Migration](https://phpunit.de/announcements/phpunit-11.html)

