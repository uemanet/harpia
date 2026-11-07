# Fix - Missing Required Parameter em `geral.pessoas.verificapessoa`

**Data:** 2026-03-17  
**Contexto:** Erro ao acessar fluxos de verificacao de pessoa (ex.: `academico-alunos-create`, `academico-professores-create`, `academico-tutores-create`, `rh-colaboradores-create`, `seguranca-usuarios-create`).

## Erro observado

- `Missing required parameter for [Route: geral.pessoas.verificapessoa] [URI: geral/pessoas/verificapessoa/{rota}] [Missing parameter: rota]`
- View envolvida: `modulos/Geral/Views/pessoas/verificapessoa.blade.php`

## Causa raiz

No arquivo de rotas do modulo Geral existem duas rotas com o mesmo nome:

- `GET /geral/pessoas/verificapessoa/{rota}`
- `POST /geral/pessoas/verificapessoa`

Ambas nomeadas como `geral.pessoas.verificapessoa`, o que deixa a resolucao de `route('geral.pessoas.verificapessoa')` ambigua quando o parametro `{rota}` nao e fornecido.

## Correcao aplicada

### 1) Form da view `verificapessoa`

Arquivo alterado:
- `modulos/Geral/Views/pessoas/verificapessoa.blade.php`

Alteracao:
- De: `action="{{ route('geral.pessoas.verificapessoa') }}"`
- Para: `action="{{ url('geral/pessoas/verificapessoa') }}"`

Com isso, o submit sempre aponta para o endpoint POST correto, sem depender da resolucao por nome.

### 2) Verificacao adicional de padrao semelhante

Foi feita busca por nomes de rota duplicados com combinacao de URI com e sem parametro.

Pontos encontrados:

1. `geral.pessoas.verificapessoa` (corrigido neste fix)
2. `auth.reset-password` (GET com `{token}` + POST sem token)
3. `seguranca.usuarios.create` (GET com `{id?}` opcional + POST sem parametro)

Ajuste preventivo aplicado em `auth.reset-password`:

Arquivo alterado:
- `modulos/Seguranca/Views/email/forget-password.blade.php`

Alteracao:
- De: `{{ route('auth.reset-password') }}/{{$token}}`
- Para: `{{ route('auth.reset-password', ['token' => $token]) }}`

## Impacto esperado

- Elimina o erro de parametro obrigatorio ausente no fluxo de verificacao de pessoa.
- Torna a geracao de URL de reset de senha robusta contra ambiguidade de rota nomeada.

## Validacao recomendada

1. Acessar os fluxos:
- `/geral/pessoas/verificapessoa/academico-alunos-create`
- `/geral/pessoas/verificapessoa/academico-professores-create`
- `/geral/pessoas/verificapessoa/academico-tutores-create`
- `/geral/pessoas/verificapessoa/rh-colaboradores-create`
- `/geral/pessoas/verificapessoa/seguranca-usuarios-create`

2. Submeter CPF valido/invalido e confirmar que nao ocorre `Missing required parameter`.
3. Testar envio de email de reset de senha e validar abertura do link com token.
