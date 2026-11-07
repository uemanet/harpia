# Fixes Pós-Migração LaravelCollective -> HTML (Bindings Blade)

**Data:** 2026-03-17  
**Escopo:** views Blade em `modulos/**`  
**Objetivo:** corrigir problemas de binding introduzidos na migração automática de `Form::` para HTML nativo.

## Problemas corrigidos

1. `value` de `<input>` com expressão PHP literal (não renderizada pelo Blade)
- **Antes:** `value="isset($model->campo) ? $model->campo : old('campo')"`
- **Depois:** `value="{{ isset($model->campo) ? $model->campo : old('campo') }}"`

2. Conteúdo de `<textarea>` com expressão PHP literal (não renderizada)
- **Antes:** `>isset($model->campo) ? $model->campo : old('campo')</textarea>`
- **Depois:** `>{{ isset($model->campo) ? $model->campo : old('campo') }}</textarea>`

3. Condição de `selected` com precedência incorreta no ternário
- **Antes:** `{{ isset($m->f) ? $m->f : old('f') == $key ? 'selected' : '' }}`
- **Depois:** `{{ (isset($m->f) ? $m->f : old('f')) == $key ? 'selected' : '' }}`

## Arquivos alterados

- `modulos/Academico/Views/professores/includes/formulario.blade.php`
- `modulos/RH/Views/colaboradores/includes/formulario.blade.php`
- `modulos/RH/Views/colaboradores/includes/formulario_edit.blade.php`
- `modulos/RH/Views/colaboradores/includes/formulario_matricula.blade.php`
- `modulos/RH/Views/colaboradores/includes/formulario_movimentacao_setor.blade.php`

## Quantitativo dos ajustes

- Inputs `value` corrigidos: **26**
- Textareas corrigidas: **2**
- Condições `selected` corrigidas: **43**
- Total de linhas alteradas nos arquivos de view: **71**

## Validação executada

Foram executadas varreduras globais em `modulos/**/*.blade.php` e não restaram ocorrências dos padrões quebrados:

- `value="...isset(...old(..."` (sem `{{ }}`)
- `<textarea>isset(...old(...</textarea>` (sem `{{ }}`)
- `: old('...') == ... ? 'selected' : ''` sem agrupamento do valor base

## Observações

- Os comentários `Form model: ... inputs devem usar old(...)` permanecem no código e podem ser tratados em uma etapa separada de limpeza.
- Este fix concentrou-se exclusivamente em bugs funcionais de renderização/preenchimento pós-migração.

## Segunda varredura (incremento)

### Escopo da segunda análise

- Revisão de possíveis regressões semânticas de preenchimento em formulários de edição (casos com `old()` sem fallback explícito do model).
- Correlação com arquivos marcados pelo comentário `Form model:` gerado na migração automática.

### Resultado da segunda varredura

- Arquivos com comentário `Form model:` ainda presentes: **34**
- Ocorrências globais de `value="{{ old('...') }}"`: **154**
- Arquivos distintos com `value="{{ old('...') }}"`: **54**
- Ocorrências globais de `<textarea> {{ old('...') }} </textarea>`: **14**

### Priorização de risco (Form model + old sem fallback)

Foram encontrados dois arquivos com sinal forte de possível regressão em edição (form model no container e campos com `old()` sem fallback):

1. `modulos/Seguranca/Views/auth/profile/index.blade.php`
- `value` com `old()` sem fallback: **16**
- `selected` com `old()` sem fallback explícito: **28**

2. `modulos/Academico/Views/ofertasdisciplinas/edit.blade.php`
- `value` com `old()` sem fallback: **1**
- `selected` com `old()` sem fallback explícito: **3**

### Conclusão da segunda varredura

- **Não** foram encontrados novos casos dos bugs críticos já corrigidos (expressão literal sem Blade e ternário `selected` com precedência incorreta).
- A pendência principal remanescente é de **consistência funcional de edição** (fallback de model), especialmente em telas com `Form model:`.

## Terceira varredura (prioridade do old)

### Motivação

- Foi validado que a expressão abaixo, apesar de sintaticamente válida, possui problema de UX em edição:
	- `{{ (isset($pessoa->pes_estado) ? $pessoa->pes_estado : old('pes_estado')) == 'AC' ? 'selected' : '' }}`
- Em caso de erro de validação, essa lógica pode priorizar o valor do model e descartar a escolha mais recente do usuário.

### Padrão aplicado

- Padrão antigo (model-first):
	- `isset($model->campo) ? $model->campo : old('campo')`
- Padrão novo (old-first, recomendado no Laravel):
	- `old('campo', isset($model->campo) ? $model->campo : null)`

Exemplo de `selected`:

- Antes:
	- `{{ (isset($pessoa->pes_estado) ? $pessoa->pes_estado : old('pes_estado')) == 'AC' ? 'selected' : '' }}`
- Depois:
	- `{{ old('pes_estado', isset($pessoa->pes_estado) ? $pessoa->pes_estado : null) == 'AC' ? 'selected' : '' }}`

### Correções aplicadas nesta varredura

Arquivos ajustados com normalização de prioridade para `old`:

- `modulos/Academico/Views/professores/includes/formulario.blade.php`
- `modulos/Geral/Views/pessoas/includes/formulario.blade.php`
- `modulos/RH/Views/colaboradores/includes/formulario.blade.php`
- `modulos/RH/Views/colaboradores/includes/formulario_edit.blade.php`
- `modulos/RH/Views/colaboradores/includes/formulario_matricula.blade.php`
- `modulos/RH/Views/colaboradores/includes/formulario_movimentacao_setor.blade.php`
- `modulos/Geral/Views/pessoas/verificapessoa.blade.php`
- `modulos/Alunos/Views/comprovante/verifica.blade.php`

### Validação desta varredura

- Ocorrências restantes de model-first (`isset(...) ? ... : old(...)`): **0**
- Ocorrências restantes de selected model-first (`(isset(...) ? ... : old(...)) == ...`): **0**
- Ocorrências restantes de selected com `isset(...) && old(...) == ...`: **0**
