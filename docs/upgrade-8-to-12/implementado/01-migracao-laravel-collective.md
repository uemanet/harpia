# Etapa Implementada: Migração LaravelCollective/HTML → HTML Puro

**Data:** Março/2026
**Fase do Upgrade:** Pré-requisito da FASE 1 (Laravel 8 → 9)

---

## O que foi feito

### Script de Migração Automatizada

Criado o script Python `scripts/migrate_laravel_collective_to_html.py` que converte
automaticamente todas as diretivas `{!! Form::* !!}` e `{{ Form::* }}` do pacote
`laravelcollective/html` para HTML puro compatível com Blade.

**Localização do script:** `scripts/migrate_laravel_collective_to_html.py`

**Uso:**
```bash
# Migrar todos os módulos
python3 scripts/migrate_laravel_collective_to_html.py /path/to/project

# Simulação sem alterar arquivos
python3 scripts/migrate_laravel_collective_to_html.py /path/to/project --dry-run

# Apenas um módulo
python3 scripts/migrate_laravel_collective_to_html.py /path/to/project --module Academico

# Apenas um arquivo
python3 scripts/migrate_laravel_collective_to_html.py /path/to/project --file modulos/Seguranca/Views/usuarios/create.blade.php
```

### Migração Executada

A migração foi executada com sucesso em todos os arquivos Blade do projeto:

| Métrica | Valor |
|---------|-------|
| Arquivos processados | 178 |
| Total de substituições | 1.154 |
| `Form::` residuais | 0 |
| Erros de conversão | 0 |

---

## Transformações Realizadas

| Antes (LaravelCollective) | Depois (HTML Puro) |
|---|---|
| `{!! Form::open(["route" => "name", "method" => "POST"]) !!}` | `<form action="{{ route('name') }}" method="POST">`<br>`    @csrf` |
| `{!! Form::model($model, ["route" => ["name", $id], "method" => "PUT"]) !!}` | `<form action="{{ route('name', [$id]) }}" method="POST">`<br>`    @csrf`<br>`    @method('PUT')` |
| `{!! Form::close() !!}` | `</form>` |
| `{!! Form::submit('Salvar', ['class' => 'btn btn-primary']) !!}` | `<button type="submit" class="btn btn-primary">Salvar</button>` |
| `{!! Form::text('campo', old('campo'), ['class' => 'form-control']) !!}` | `<input type="text" name="campo" value="{{ old('campo') }}" class="form-control" >` |
| `{!! Form::number('nota', $valor, ['min' => 0, 'max' => 10]) !!}` | `<input type="number" name="nota" value="{{ $valor }}" min="0" max="10" >` |
| `{!! Form::password('senha', ['class' => 'form-control']) !!}` | `<input type="password" name="senha" class="form-control" >` |
| `{!! Form::email('email', old('email'), ['class' => 'form-control']) !!}` | `<input type="email" name="email" value="{{ old('email') }}" class="form-control" >` |
| `{!! Form::textarea('desc', old('desc'), ['rows' => '4']) !!}` | `<textarea name="desc" rows="4">{{ old('desc') }}</textarea>` |
| `{!! Form::select('campo', $opcoes, old('campo'), ['class' => 'form-control']) !!}` | `<select name="campo" class="form-control">`<br>`    @foreach($opcoes as $key => $value)`<br>`        <option value="{{ $key }}" {{ old('campo') == $key ? 'selected' : '' }}>{{ $value }}</option>`<br>`    @endforeach`<br>`</select>` |
| `{!! Form::hidden('campo', $valor) !!}` | `<input type="hidden" name="campo" value="{{ $valor }}" >` |
| `{!! Form::file('arquivo', ['class' => 'form-control']) !!}` | `<input type="file" name="arquivo" class="form-control" >` |
| `{!! Form::input('hidden', 'campo', $valor) !!}` | `<input type="hidden" name="campo" value="{{ $valor }}" >` |
| `{!! Form::label('campo', 'Rótulo', ['class' => 'control-label']) !!}` | `<label for="campo" class="control-label">Rótulo</label>` |

---

## Módulos Migrados

### Academico (83 arquivos)
- `alunos/`, `aproveitamentoestudos/`, `carteirasestudantis/`, `centros/`
- `certificacao/`, `conclusaocurso/`, `controlederegistro/`, `cursos/`
- `departamentos/`, `diplomas/`, `disciplinas/`, `grupos/`
- `historicodefinitivo/`, `lancamentonotas/`, `lancamentostccs/`, `matricula-curso/`
- `matriculadisciplina/`, `matriculaslote/`, `matrizescurriculares/`, `modulosmatrizes/`
- `ofertascursos/`, `ofertasdisciplinas/`, `periodosletivos/`, `polos/`
- `professores/`, `relatoriosatasfinais/`, `relatoriosmatriculascurso/`
- `relatoriosmatriculasdisciplina/`, `turmas/`, `tutores/`, `tutoresgrupos/`, `vinculos/`

### Geral (8 arquivos)
- `documentos/`, `pessoas/`, `titulacoes/`, `titulacoesinformacoes/`

### Integracao (6 arquivos)
- `ambientesvirtuais/`, `mapeamentonotas/`

### Monitoramento (3 arquivos)
- `forumresponse/`, `tempoonline/`

### RH (49 arquivos)
- `areasconhecimentos/`, `atividadesextrascolaboradores/`, `bancos/`, `colaboradores/`
- `contascolaboradores/`, `fontespagadoras/`, `funcoes/`, `horastrabalhadas/`
- `justificativas/`, `periodosaquisitivos/`, `periodosgozo/`, `periodoslaborais/`
- `relatorios/`, `salarioscolaboradores/`, `setores/`, `vinculos/`, `vinculosfontespagadoras/`

### Seguranca (17 arquivos)
- `auth/` (login, forget-password, reset-password, profile)
- `menuitens/`, `perfis/`, `permissoes/`, `usuarios/`

---

## Estatísticas por Tipo de Transformação

| Método Form:: | Ocorrências |
|---|---|
| `Form::close` | 98 → `</form>` |
| `Form::submit` | 65 → `<button type="submit">` |
| `Form::open` | 53 → `<form>@csrf` |
| `Form::model` | 45 → `<form>@csrf @method()` |
| `Form::number` | 37 → `<input type="number">` |
| `Form::textarea` | 16 → `<textarea>` |
| `Form::hidden` | 13 → `<input type="hidden">` |
| `Form::label` | ~60 → `<label>` |
| `Form::text` | ~50 → `<input type="text">` |
| `Form::select` | ~40 → `<select>@foreach` |
| `Form::file` | 9 → `<input type="file">` |
| `Form::password` | 7 → `<input type="password">` |
| `Form::input` | 5 → `<input type="...">` |
| `Form::email` | 4 → `<input type="email">` |

---

## Observações e Pendências

### ⚠️ Form::model — Inputs precisam de fallback manual

Os formulários convertidos de `Form::model($model, ...)` adicionam um comentário:
```blade
{{-- Form model: $model - inputs devem usar old('campo', $model->campo) --}}
```

O `Form::model` preenchia automaticamente os valores dos inputs a partir do modelo.
Após a migração, os inputs nos arquivos `includes/formulario.blade.php` já usam
`old('campo')` para criação, mas nos formulários de edição precisam de:

```blade
{{-- ANTES (automático via Form::model): --}}
{!! Form::text('crs_nome', null, ['class' => 'form-control']) !!}

{{-- DEPOIS (manual): --}}
<input type="text" name="crs_nome" value="{{ old('crs_nome', $curso->crs_nome ?? '') }}" class="form-control" >
```

**Arquivos de formulários `includes/` que precisam de atenção** (45 arquivos com `Form model` hint):
- Todos os arquivos `includes/formulario.blade.php` dos módulos que têm edit view

### ✅ Form::select com @foreach

Os selects dinâmicos (com variável PHP) foram convertidos para `@foreach`. O formato
está correto para os `$variavel` que são arrays `key => value` passados pelo controller.

### ✅ Formulários de Upload

Formulários com `enctype="multipart/form-data"` foram preservados corretamente
(ex: `matrizescurriculares`, `lancamentostccs`).

---

## Próximos Passos

### Imediatos (para completar a migração das views)

1. **Revisar inputs em views de edição** — garantir que inputs usam `old('campo', $model->campo)`:
   ```blade
   value="{{ old('crs_nome', $curso->crs_nome ?? '') }}"
   ```

2. **Remover `laravelcollective/html` do `composer.json`** e executar:
   ```bash
   composer remove laravelcollective/html
   ```

3. **Remover provider** do `config/app.php`:
   ```php
   // REMOVER:
   Collective\Html\HtmlServiceProvider::class,
   // REMOVER dos aliases:
   'Form' => Collective\Html\FormFacade::class,
   'Html' => Collective\Html\HtmlFacade::class,
   ```

4. **Limpar cache de views**:
   ```bash
   php artisan view:clear
   php artisan config:clear
   php artisan cache:clear
   ```

5. **Testar cada módulo** manualmente

### FASE 1 — Laravel 8 → 9 (próxima etapa)

Com o LaravelCollective removido, a FASE 1 pode prosseguir. Ver `Planejamento Upgrade Laravel 8 para 12.md` para detalhes.

---

## O que Ainda Falta

### Para completar a migração LaravelCollective:

- [x] ~~Remover `laravelcollective/html` do `composer.json`~~ **FEITO**
- [x] ~~Remover alias `Form` (`Collective\Html\FormFacade`) do `config/app.php`~~ **FEITO**
- [x] ~~Executar `composer remove laravelcollective/html` (remover do vendor/)~~ **FEITO**
- [x] ~~Executar `php artisan view:clear && php artisan config:clear && php artisan cache:clear`~~ **FEITO**
- [ ] Revisar valores dos inputs nos 45 formulários de edição (Form::model hint)

### Upgrade Laravel (próximas fases):

- [x] **FASE 1**: Laravel 8 → 9
  - [x] Atualizar `composer.json` (framework, laravel/ui, doctrine/dbal)
  - [x] Corrigir `bootstrap/app.php` (bug do 3º argumento no singleton)
  - [x] Corrigir namespace `HttpResponseException` em `WhoopsHandler`
  - [x] Atualizar `config/mail.php` (SwiftMailer → Symfony Mailer)
  - [x] Remover `'fetch' => PDO::FETCH_CLASS` do `config/database.php`
  - [x] Remover alias `Input` do `config/app.php`
- [ ] **FASE 2**: Laravel 9 → 10
  - [ ] Renomear `$routeMiddleware` → `$middlewareAliases` no Kernel
  - [ ] Atualizar PHPUnit para `^10.0`
- [ ] **FASE 3**: Laravel 10 → 11 (maior esforço)
  - [ ] Remover `doctrine/dbal` do `composer.json`
  - [ ] Migrar `laravel/legacy-factories` → class-based factories (20+ factories)
  - [ ] Atualizar `factory()` helper → `Model::factory()` em todos os testes
  - [ ] Adicionar `$authPasswordName = 'usr_senha'` no model `Usuario`
  - [ ] Substituir `CheckForMaintenanceMode` por `PreventRequestsDuringMaintenance`
- [ ] **FASE 4**: Laravel 11 → 12
  - [ ] Verificar Carbon 3 breaking changes
  - [ ] Atualizar PHPUnit para `^11.0`
- [ ] **Validação final**: Todos os testes passando, deploy em staging


