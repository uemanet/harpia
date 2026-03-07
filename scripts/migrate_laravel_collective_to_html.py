#!/usr/bin/env python3
"""
Script de Migração: LaravelCollective/HTML → HTML puro (Blade)
Projeto: Harpia - UEMA
Data: Março/2026
Este script converte automaticamente as diretivas {!! Form::* !!} do LaravelCollective
para HTML puro equivalente nos arquivos Blade do projeto.
Transformações realizadas:
  - Form::open(...)     → <form ...>\n    @csrf
  - Form::model(...)    → <form ...>\n    @csrf\n    @method('PUT'/'PATCH')
  - Form::close()       → </form>
  - Form::submit(...)   → <button type="submit" ...>text</button>
  - Form::text(...)     → <input type="text" ...>
  - Form::number(...)   → <input type="number" ...>
  - Form::password(...) → <input type="password" ...>
  - Form::email(...)    → <input type="email" ...>
  - Form::textarea(...) → <textarea ...>value</textarea>
  - Form::select(...)   → <select ...>@foreach...</select>
  - Form::hidden(...)   → <input type="hidden" ...>
  - Form::file(...)     → <input type="file" ...>
  - Form::input(...)    → <input type=... ...>
  - Form::label(...)    → <label ...>text</label>
"""
import re
import os
import sys
import argparse
from pathlib import Path
# ─── Helpers ─────────────────────────────────────────────────────────────────
def extract_args(content: str) -> list:
    """
    Extrai argumentos de uma chamada de função separando por vírgulas,
    mas respeitando parênteses, colchetes e aspas.
    """
    args = []
    depth = 0
    current = ''
    in_single = False
    in_double = False
    i = 0
    while i < len(content):
        c = content[i]
        if c == "'" and not in_double:
            in_single = not in_single
            current += c
        elif c == '"' and not in_single:
            in_double = not in_double
            current += c
        elif c in '([{' and not in_single and not in_double:
            depth += 1
            current += c
        elif c in ')]}' and not in_single and not in_double:
            depth -= 1
            current += c
        elif c == ',' and depth == 0 and not in_single and not in_double:
            args.append(current.strip())
            current = ''
        else:
            current += c
        i += 1
    if current.strip():
        args.append(current.strip())
    return args
def strip_quotes(s: str) -> str:
    s = s.strip()
    if len(s) >= 2 and ((s[0] == "'" and s[-1] == "'") or (s[0] == '"' and s[-1] == '"')):
        return s[1:-1]
    return s
def parse_attr_array(raw: str) -> dict:
    """
    Faz parse de um array PHP de atributos HTML.
    """
    raw = raw.strip()
    if raw.startswith('[') and raw.endswith(']'):
        raw = raw[1:-1]
    elif raw.startswith('array(') and raw.endswith(')'):
        raw = raw[6:-1]
    result = {}
    pattern = re.compile(
        r"""['"]([\w\-]+)['"]\s*=>\s*"""
        r"""(['""][^'"]*['""]|\$[\w\->'\[\]"\.]+(?:\s*\.\s*[\w\$'"\/\(\)]+)*|[\w.\/\(\)]+)""",
        re.DOTALL
    )
    for m in pattern.finditer(raw):
        key = m.group(1)
        val = m.group(2).strip()
        if len(val) >= 2 and ((val[0] == "'" and val[-1] == "'") or (val[0] == '"' and val[-1] == '"')):
            val = val[1:-1]
        result[key] = val
    return result
def attr_dict_to_html(d: dict, skip: set = None) -> str:
    """Converte dict de atributos em string HTML."""
    if skip is None:
        skip = set()
    parts = []
    for k, v in d.items():
        if k.lower() in skip:
            continue
        if v is True or v == '':
            parts.append(k)
        else:
            parts.append(f'{k}="{v}"')
    return ' '.join(parts)
def resolve_route_from_attr(attr_str: str):
    """
    Extrai a route de um array de atributos.
    Suporta:
      "route" => "name"
      "route" => ["name", $param]
    """
    # Try "route" => "simple.route.name"
    simple = re.search(r"""['"]route['"]\s*=>\s*['"]([^'"]+)['"]""", attr_str)
    if simple:
        return "{{ route('" + simple.group(1) + "') }}"
    # Try "route" => ["name", $param1, $param2, ...]
    arr = re.search(r"""['"]route['"]\s*=>\s*\[(.*?)\]""", attr_str, re.DOTALL)
    if arr:
        parts_raw = arr.group(1)
        parts = extract_args(parts_raw)
        if parts:
            route_name = strip_quotes(parts[0])
            params = [p.strip() for p in parts[1:]]
            if params:
                params_str = ', '.join(params)
                return "{{ route('" + route_name + "', [" + params_str + "]) }}"
            else:
                return "{{ route('" + route_name + "') }}"
    return None
# ─── Conversores individuais ──────────────────────────────────────────────────
def convert_form_open_or_model(is_model: bool, args: list) -> str:
    attr_str = ''
    model_var = ''
    if is_model:
        model_var = args[0].strip() if args else ''
        attr_str = args[1].strip() if len(args) > 1 else ''
    else:
        attr_str = args[0].strip() if args else ''
    attrs = parse_attr_array(attr_str)
    form_method = attrs.get('method', 'PUT' if is_model else 'POST').upper()
    enctype = attrs.get('enctype', '')
    form_id = attrs.get('id', '')
    role = attrs.get('role', '')
    route_action = resolve_route_from_attr(attr_str)
    if not route_action:
        url_val = attrs.get('url', '')
        if url_val:
            route_action = url_val
        else:
            route_action = '#'
    html_method = form_method if form_method in ('GET', 'POST') else 'POST'
    parts = [f'<form action="{route_action}" method="{html_method}"']
    if form_id:
        parts.append(f'id="{form_id}"')
    if role:
        parts.append(f'role="{role}"')
    if enctype:
        parts.append(f'enctype="{enctype}"')
    form_tag = ' '.join(parts) + '>'
    lines = [form_tag, '    @csrf']
    if form_method not in ('GET', 'POST'):
        lines.append(f"    @method('{form_method}')")
    if is_model and model_var:
        lines.append("    {{-- Form model: " + model_var + " - inputs devem usar old('campo', " + model_var + "->campo) --}}")
    return '\n'.join(lines)
def convert_form_close(_args: list) -> str:
    return '</form>'
def convert_form_submit(args: list) -> str:
    label = strip_quotes(args[0]) if args else 'Salvar'
    attrs = {}
    if len(args) > 1:
        attrs = parse_attr_array(args[1])
    attrs_str = attr_dict_to_html(attrs)
    return f'<button type="submit"{(" " + attrs_str) if attrs_str else ""}>{label}</button>'
def convert_input_type(input_type: str, args: list) -> str:
    name = strip_quotes(args[0]) if args else ''
    value = args[1].strip() if len(args) > 1 else ''
    attrs = {}
    if len(args) > 2:
        attrs = parse_attr_array(args[2])
    # Normalise value
    if value in ('null', 'NULL', ''):
        val_attr = ''
    elif value.startswith('old('):
        val_attr = f'{{{{ {value} }}}}'
    elif value.startswith('$') or value.startswith('{{'):
        val_attr = value if value.startswith('{{') else f'{{{{ {value} }}}}'
    elif value.startswith('"') or value.startswith("'"):
        val_attr = strip_quotes(value)
    else:
        val_attr = value
    attrs_str = attr_dict_to_html(attrs)
    parts = [f'<input type="{input_type}"', f'name="{name}"']
    if val_attr and input_type not in ('password', 'file'):
        parts.append(f'value="{val_attr}"')
    if attrs_str:
        parts.append(attrs_str)
    parts.append('>')
    return ' '.join(parts)
def convert_form_hidden(args: list) -> str:
    name = strip_quotes(args[0]) if args else ''
    value = args[1].strip() if len(args) > 1 else ''
    attrs = {}
    if len(args) > 2:
        attrs = parse_attr_array(args[2])
    if value in ('null', 'NULL', ''):
        val_attr = ''
    elif value.startswith('$') or value.startswith('{{'):
        val_attr = value if value.startswith('{{') else f'{{{{ {value} }}}}'
    elif value.startswith('"') or value.startswith("'"):
        val_attr = strip_quotes(value)
    else:
        val_attr = value
    attrs_str = attr_dict_to_html(attrs)
    parts = ['<input type="hidden"', f'name="{name}"']
    if val_attr:
        parts.append(f'value="{val_attr}"')
    if attrs_str:
        parts.append(attrs_str)
    parts.append('>')
    return ' '.join(parts)
def convert_form_file(args: list) -> str:
    name = strip_quotes(args[0]) if args else ''
    attrs = {}
    if len(args) > 1:
        attrs = parse_attr_array(args[1])
    attrs_str = attr_dict_to_html(attrs)
    parts = ['<input type="file"', f'name="{name}"']
    if attrs_str:
        parts.append(attrs_str)
    parts.append('>')
    return ' '.join(parts)
def convert_form_input(args: list) -> str:
    input_type = strip_quotes(args[0]) if args else 'text'
    return convert_input_type(input_type, args[1:])
def convert_form_label(args: list) -> str:
    for_attr = strip_quotes(args[0]) if args else ''
    text = strip_quotes(args[1]) if len(args) > 1 else ''
    attrs = {}
    if len(args) > 2:
        attrs = parse_attr_array(args[2])
    attrs_str = attr_dict_to_html(attrs)
    parts = [f'<label for="{for_attr}"']
    if attrs_str:
        parts.append(attrs_str)
    return ' '.join(parts) + f'>{text}</label>'
def convert_form_textarea(args: list) -> str:
    name = strip_quotes(args[0]) if args else ''
    value = args[1].strip() if len(args) > 1 else ''
    attrs = {}
    if len(args) > 2:
        attrs = parse_attr_array(args[2])
    attrs_str = attr_dict_to_html(attrs)
    if value in ('null', 'NULL', ''):
        content_val = ''
    elif value.startswith('old('):
        content_val = f'{{{{ {value} }}}}'
    elif value.startswith('$'):
        content_val = f'{{{{ {value} }}}}'
    elif value.startswith('"') or value.startswith("'"):
        content_val = strip_quotes(value)
    else:
        content_val = value
    parts = [f'<textarea name="{name}"']
    if attrs_str:
        parts.append(attrs_str)
    return ' '.join(parts) + f'>{content_val}</textarea>'
def convert_form_select(args: list) -> str:
    name = strip_quotes(args[0]) if args else ''
    options_var = args[1].strip() if len(args) > 1 else ''
    selected = args[2].strip() if len(args) > 2 else 'null'
    attrs = {}
    if len(args) > 3:
        attrs = parse_attr_array(args[3])
    placeholder = attrs.pop('placeholder', None)
    attrs_str = attr_dict_to_html(attrs)
    parts = [f'<select name="{name}"']
    if attrs_str:
        parts.append(attrs_str)
    select_open = ' '.join(parts) + '>'
    lines = [select_open]
    if placeholder:
        lines.append(f'    <option value="">{placeholder}</option>')
    if options_var.startswith('[') and '=>' in options_var:
        # Inline static array - supports both quoted and numeric keys
        inner = options_var[1:-1]
        option_pattern = re.compile(r"""['"]?([\w\s\-_]+)['"]?\s*=>\s*['"]([\w\s\-_çãõáéíóúâêîôûàèìòùü,!?]+)['"]""")
        found = False
        for m in option_pattern.finditer(inner):
            key = m.group(1).strip()
            val = m.group(2)
            if selected and selected not in ('null', 'NULL'):
                sel_str = " {{ " + selected + " == '" + key + "' ? 'selected' : '' }}"
            else:
                sel_str = ''
            lines.append('    <option value="' + key + '"' + sel_str + '>' + val + '</option>')
            found = True
            found = True
        if not found:
            lines.append(f'    {{-- TODO: popular opções de {options_var} --}}')
    elif options_var == '[]' or options_var == '':
        # Empty array - dynamic via JS probably
        pass
    else:
        # Dynamic PHP variable
        if selected and selected not in ('null', 'NULL'):
            lines.append(f'    @foreach({options_var} as $key => $value)')
            lines.append(f"        <option value=\"{{{{ $key }}}}\" {{{{ {selected} == $key ? 'selected' : '' }}}}>{{{{ $value }}}}</option>")
            lines.append('    @endforeach')
        else:
            lines.append(f'    @foreach({options_var} as $key => $value)')
            lines.append(f'        <option value="{{{{ $key }}}}">{{{{ $value }}}}</option>')
            lines.append('    @endforeach')
    lines.append('</select>')
    return '\n'.join(lines)
# ─── Regex e dispatcher ───────────────────────────────────────────────────────
FORM_PATTERN = re.compile(
    r'\{!!\s*Form::(\w+)\s*\((.*?)\)\s*;?\s*!!\}|\{\{\s*Form::(\w+)\s*\((.*?)\)\s*\}\}',
    re.DOTALL,
)
def dispatch_form_call(method: str, args: list, original: str) -> str:
    method = method.lower()
    try:
        if method == 'open':
            return convert_form_open_or_model(False, args)
        elif method == 'model':
            return convert_form_open_or_model(True, args)
        elif method == 'close':
            return convert_form_close(args)
        elif method == 'submit':
            return convert_form_submit(args)
        elif method == 'text':
            return convert_input_type('text', args)
        elif method == 'number':
            return convert_input_type('number', args)
        elif method == 'password':
            return convert_input_type('password', args)
        elif method == 'email':
            return convert_input_type('email', args)
        elif method == 'textarea':
            return convert_form_textarea(args)
        elif method == 'select':
            return convert_form_select(args)
        elif method == 'hidden':
            return convert_form_hidden(args)
        elif method == 'file':
            return convert_form_file(args)
        elif method == 'input':
            return convert_form_input(args)
        elif method == 'label':
            return convert_form_label(args)
        else:
            return f'{{-- TODO: Form::{method} não convertido automaticamente --}}\n{{-- {original[:120]} --}}'
    except Exception as e:
        return f'{{-- ERRO AO CONVERTER: {e} --}}\n{{-- {original[:120]} --}}'
def process_content(content: str) -> tuple:
    count = [0]
    def replacer(match):
        count[0] += 1
        # Group 1,2 for {!! Form:: !!} and group 3,4 for {{ Form:: }}
        method = match.group(1) or match.group(3)
        args_raw = (match.group(2) or match.group(4) or '').strip()
        args = extract_args(args_raw)
        return dispatch_form_call(method, args, match.group(0))
    new_content = FORM_PATTERN.sub(replacer, content)
    return new_content, count[0]
def process_file(filepath: Path, dry_run: bool = False):
    try:
        content = filepath.read_text(encoding='utf-8')
    except Exception as e:
        print(f"  [ERRO] Não foi possível ler {filepath}: {e}")
        return False, 0
    if 'Form::' not in content:
        return False, 0
    new_content, count = process_content(content)
    if new_content == content or count == 0:
        return False, 0
    if not dry_run:
        backup_path = filepath.with_suffix(filepath.suffix + '.bak')
        content_backup = content
        try:
            filepath.write_text(new_content, encoding='utf-8')
        except Exception as e:
            print(f"  [ERRO] Não foi possível escrever {filepath}: {e}")
            return False, 0
    return True, count
def find_blade_files(root: Path) -> list:
    files = []
    for path in sorted(root.rglob('*.blade.php')):
        try:
            if 'Form::' in path.read_text(encoding='utf-8'):
                files.append(path)
        except Exception:
            pass
    return files
def main():
    parser = argparse.ArgumentParser(
        description='Migra LaravelCollective Form:: para HTML puro em arquivos Blade'
    )
    parser.add_argument('root', nargs='?', default='.',
                        help='Diretório raiz do projeto (default: .)')
    parser.add_argument('--dry-run', action='store_true',
                        help='Simula as conversões sem alterar arquivos')
    parser.add_argument('--file', help='Processar apenas um arquivo específico')
    parser.add_argument('--module',
                        help='Processar apenas um módulo (ex: Academico, RH, Seguranca)')
    args = parser.parse_args()
    root = Path(args.root).resolve()
    if args.file:
        files = [Path(args.file).resolve()]
    elif args.module:
        module_path = root / 'modulos' / args.module
        if not module_path.exists():
            print(f"[ERRO] Módulo não encontrado: {module_path}")
            sys.exit(1)
        files = find_blade_files(module_path)
    else:
        files = find_blade_files(root / 'modulos')
    if not files:
        print("Nenhum arquivo Blade com Form:: encontrado.")
        return
    mode = "[DRY RUN] " if args.dry_run else ""
    print(f"\n{mode}Migração LaravelCollective → HTML puro")
    print(f"Raiz: {root}")
    print(f"Arquivos encontrados com Form::: {len(files)}\n")
    print("=" * 70)
    total_files = 0
    total_replacements = 0
    remaining = []
    for filepath in files:
        try:
            rel_path = filepath.relative_to(root)
        except ValueError:
            rel_path = filepath
        modified, count = process_file(filepath, dry_run=args.dry_run)
        if modified:
            total_files += 1
            total_replacements += count
            status = "✓" if not args.dry_run else "~"
            print(f"  {status} {rel_path} ({count} substituições)")
        else:
            # Check if Form:: still present (pattern not matched)
            try:
                curr = filepath.read_text(encoding='utf-8')
                if 'Form::' in curr:
                    remaining.append(str(rel_path))
                    print(f"  ⚠ {rel_path} (Form:: residual - verificar manualmente)")
            except Exception:
                pass
    print("\n" + "=" * 70)
    print(f"\n{mode}Resumo:")
    print(f"  Arquivos processados: {total_files}")
    print(f"  Total de substituições: {total_replacements}")
    if remaining:
        print(f"\n  ⚠  Arquivos com Form:: residual ({len(remaining)}):")
        for r in remaining:
            print(f"     - {r}")
    if not args.dry_run:
        print(f"\n✅ Concluído!")
        print(f"\nPróximos passos:")
        print(f"  1. Revisar Form::select convertidos (opções podem precisar de ajuste)")
        print("  2. Verificar Form::model - confirmar old('campo', $model->campo) nos inputs")
        print(f"  3. Confirmar enctype='multipart/form-data' em formulários de upload")
        print(f"  4. Remover 'laravelcollective/html' do composer.json")
        print(f"  5. php artisan view:clear && php artisan config:clear")
        print(f"  6. Testar cada módulo")
    else:
        print(f"\nExecute sem --dry-run para aplicar as mudanças.")
if __name__ == '__main__':
    main()
