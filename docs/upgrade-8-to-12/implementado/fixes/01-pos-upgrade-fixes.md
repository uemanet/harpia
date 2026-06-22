# Fixes Pos-Upgrade Laravel 12

**Data:** Marco/2026

---

## Fix 01 - /rh/periodoslaborais

### Erro
- `Method formatLocalized does not exist`.
- Origem no stack: `modulos/RH/Models/PeriodoLaboral.php`.

### Causa
- O projeto passou a usar Carbon 3 no Laravel 12, e `formatLocalized()` nao esta mais disponivel.

### Solucao aplicada
- Substituicao de `->formatLocalized('%d/%m/%Y')` por `->format('d/m/Y')`.
- Aplicado em `modulos/RH/Models/PeriodoLaboral.php`.

---

## Fix 02 - /rh/horastrabalhadas

### Erro
- `Carbon\Exceptions\UnknownMethodException: Method formatLocalized does not exist`.
- Origem no stack: `modulos/RH/Models/PeriodoLaboral.php` (accessor de data usado pela listagem).

### Causa
- Mesma incompatibilidade Carbon 3 x `formatLocalized()`.

### Solucao aplicada
- Correcao herdada do Fix 01 no model `PeriodoLaboral`.
- Acesso a `/rh/horastrabalhadas` deixa de disparar a exception de metodo inexistente.

---

## Fix 03 - /geral/pessoas/verificapessoa/seguranca-usuarios-create

### Erro
- `Missing required parameter for [Route: geral.pessoas.verificapessoa]`.
- View: `modulos/Geral/Views/pessoas/verificapessoa.blade.php`.

### Causa
- O form usava `route('geral.pessoas.verificapessoa')`.
- Existe rota GET nomeada com parametro `{rota}` e rota POST com mesmo nome, gerando ambiguidade de resolucao.

### Solucao aplicada
- Alterado `action` do form para URL explicita do endpoint POST:
  - de: `route('geral.pessoas.verificapessoa')`
  - para: `url('geral/pessoas/verificapessoa')`
- Arquivo alterado: `modulos/Geral/Views/pessoas/verificapessoa.blade.php`.

---

## Fix 04 - /academico/periodosletivos

### Erro
- `Method formatLocalized does not exist`.
- Origem no stack: `modulos/Academico/Models/PeriodoLetivo.php`.

### Causa
- Mesma incompatibilidade Carbon 3 x `formatLocalized()`.

### Solucao aplicada
- Substituicao para `->format('d/m/Y')` em `PeriodoLetivo`.

---

## Correcoes Proativas Adicionais

Para evitar recorrencia do erro de data em outras telas, a substituicao de `formatLocalized('%d/%m/%Y')` foi aplicada em todos os models dos modulos (`modulos/**/*.php`) que usavam esse padrao.

Arquivos impactados (padrao global):
- `modulos/RH/Models/*`
- `modulos/Academico/Models/*`
- `modulos/Geral/Models/*`

Validacao realizada:
- Busca por `formatLocalized(` em `modulos/**/*.php` sem ocorrencias apos o ajuste.

---

## Investigacao de Possiveis Erros Relacionados

Foi feita varredura de nomes de rotas duplicados em `php artisan route:list --json`.

Resultado:
- Existem varios nomes duplicados (principalmente pares create/edit em modulos academicos).
- Esse comportamento nao foi alterado neste fix, pois depende do design de rotas e pode exigir refatoracao funcional ampla.
- O caso quebrado de `verificapessoa` foi corrigido de forma pontual e segura no form.
