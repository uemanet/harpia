@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('/css/plugins/datepicker3.css') }}">
@stop

<div class="row">
    @if(isset($pessoa->pes_id))
        <input type="hidden" name="pes_id" value="{{ $pessoa->pes_id }}" >
    @endif

    <h3 class="card-title w-100 pb-3">
        <span style="font-weight: bold;"><i class="fa-solid fa-caret-right"></i> Dados Pessoais</span>
    </h3>

    <div class="form-group col-md-3 @if ($errors->has('pes_nome')) has-error @endif">
        <label for="pes_nome" class="form-label">Nome completo <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="text" name="pes_nome" value="{{ old('pes_nome', isset($pessoa->pes_nome) ? $pessoa->pes_nome : null) }}" class="form-control" >
            @if ($errors->has('pes_nome')) <p class="help-block">{{ $errors->first('pes_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('pes_email')) has-error @endif">
        <label for="pes_email" class="form-label">Email <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="email" name="pes_email" value="{{ old('pes_email', isset($pessoa->pes_email) ? $pessoa->pes_email : null) }}" class="form-control" >
            @if ($errors->has('pes_email')) <p class="help-block">{{ $errors->first('pes_email') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('doc_conteudo')) has-error @endif">
        <label for="doc_conteudo" class="form-label">CPF <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="text" name="doc_conteudo" value="{{ old('doc_conteudo', isset($pessoa->doc_conteudo) ? $pessoa->doc_conteudo : null) }}" class="form-control" >
            @if ($errors->has('doc_conteudo')) <p class="help-block">{{ $errors->first('doc_conteudo') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('prf_codigo')) has-error @endif">
        <label for="prf_codigo" class="form-label">Código/Matrícula</label>
        <div class="controls">
            <input type="text" name="prf_codigo" value="{{ old('prf_codigo', isset($pessoa->prf_codigo) ? $pessoa->prf_codigo : null) }}" class="form-control" >
            @if ($errors->has('prf_codigo')) <p class="help-block">{{ $errors->first('prf_codigo') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('pes_mae')) has-error @endif">
        <label for="pes_mae" class="form-label">Nome da mãe <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="text" name="pes_mae" value="{{ old('pes_mae', isset($pessoa->pes_mae) ? $pessoa->pes_mae : null) }}" class="form-control" >
            @if ($errors->has('pes_mae')) <p class="help-block">{{ $errors->first('pes_mae') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('pes_pai')) has-error @endif">
        <label for="pes_pai" class="form-label">Nome do pai</label>
        <div class="controls">
            <input type="text" name="pes_pai" value="{{ old('pes_pai', isset($pessoa->pes_pai) ? $pessoa->pes_pai : null) }}" class="form-control" >
            @if ($errors->has('pes_pai')) <p class="help-block">{{ $errors->first('pes_pai') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-2 @if ($errors->has('pes_sexo')) has-error @endif">
        <label for="pes_sexo" class="form-label">Sexo <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <select name="pes_sexo" class="form-control">
    <option value="">Selecione o sexo</option>
    <option value="M" {{ old('pes_sexo', isset($pessoa->pes_sexo) ? $pessoa->pes_sexo : null) == 'M' ? 'selected' : '' }}>Masculino</option>
    <option value="F" {{ old('pes_sexo', isset($pessoa->pes_sexo) ? $pessoa->pes_sexo : null) == 'F' ? 'selected' : '' }}>Feminino</option>
</select>
            @if ($errors->has('pes_sexo')) <p class="help-block">{{ $errors->first('pes_sexo') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('pes_telefone')) has-error @endif">
        <label for="pes_telefone" class="form-label">Telefone <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="text" name="pes_telefone" value="{{ old('pes_telefone', isset($pessoa->pes_telefone) ? $pessoa->pes_telefone : null) }}" class="form-control" >
            @if ($errors->has('pes_telefone')) <p class="help-block">{{ $errors->first('pes_telefone') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 @if ($errors->has('pes_nascimento')) has-error @endif">
        <label for="pes_nascimento" class="form-label">Nascimento <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="text" name="pes_nascimento" value="{{ old('pes_nascimento', isset($pessoa->pes_nascimento) ? $pessoa->pes_nascimento : null) }}" class="form-control datepicker" >
            @if ($errors->has('pes_nascimento')) <p class="help-block">{{ $errors->first('pes_nascimento') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 @if ($errors->has('pes_estado_civil')) has-error @endif">
        <label for="pes_estado_civil" class="form-label">Estado civil</label>

        <div class="controls">
            <select name="pes_estado_civil" class="form-control">
    <option value="">Selecione o estado civil</option>
    <option value="uniao_estavel" {{ old('pes_estado_civil', isset($pessoa->pes_estado_civil) ? $pessoa->getRawOriginal('pes_estado_civil') : null) == 'uniao_estavel' ? 'selected' : '' }}>União estável</option>
</select>
            @if ($errors->has('pes_estado_civil')) <p class="help-block">{{ $errors->first('pes_estado_civil') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('pes_raca')) has-error @endif">
        <label for="pes_raca" class="form-label">Cor/Raça</label>

        <div class="controls">
            <select name="pes_raca" class="form-control">
    <option value="">Selecione a raça</option>
    <option value="branca" {{ old('pes_raca', isset($pessoa->pes_raca) ? $pessoa->pes_raca : null) == 'branca' ? 'selected' : '' }}>Branca</option>
    <option value="preta" {{ old('pes_raca', isset($pessoa->pes_raca) ? $pessoa->pes_raca : null) == 'preta' ? 'selected' : '' }}>Preta</option>
    <option value="parda" {{ old('pes_raca', isset($pessoa->pes_raca) ? $pessoa->pes_raca : null) == 'parda' ? 'selected' : '' }}>Parda</option>
    <option value="amarela" {{ old('pes_raca', isset($pessoa->pes_raca) ? $pessoa->pes_raca : null) == 'amarela' ? 'selected' : '' }}>Amarela</option>
    <option value="indigena" {{ old('pes_raca', isset($pessoa->pes_raca) ? $pessoa->pes_raca : null) == 'indigena' ? 'selected' : '' }}>Indígena</option>
    <option value="outra" {{ old('pes_raca', isset($pessoa->pes_raca) ? $pessoa->pes_raca : null) == 'outra' ? 'selected' : '' }}>Outra</option>
</select>
            @if ($errors->has('pes_raca')) <p class="help-block">{{ $errors->first('pes_raca') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-3 @if ($errors->has('pes_naturalidade')) has-error @endif">
        <label for="pes_naturalidade" class="form-label">Naturalidade <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="text" name="pes_naturalidade" value="{{ old('pes_naturalidade', isset($pessoa->pes_naturalidade) ? $pessoa->pes_naturalidade : null) }}" class="form-control" >
            @if ($errors->has('pes_naturalidade')) <p class="help-block">{{ $errors->first('pes_naturalidade') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('pes_nacionalidade')) has-error @endif">
        <label for="pes_nacionalidade" class="form-label">Nacionalidade <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="text" name="pes_nacionalidade" value="{{ old('pes_nacionalidade', isset($pessoa->pes_nacionalidade) ? $pessoa->pes_nacionalidade : null) }}" class="form-control" >
            @if ($errors->has('pes_nacionalidade')) <p class="help-block">{{ $errors->first('pes_nacionalidade') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('pes_necessidade_especial')) has-error @endif">
        <label for="pes_necessidade_especial" class="form-label">Necessidade especial?</label>

        <div class="controls">
            <select name="pes_necessidade_especial" class="form-control">
    <option value="N" {{ old('pes_necessidade_especial', isset($pessoa->pes_necessidade_especial) ? $pessoa->pes_necessidade_especial : null) == 'N' ? 'selected' : '' }}>Não</option>
    <option value="S" {{ old('pes_necessidade_especial', isset($pessoa->pes_necessidade_especial) ? $pessoa->pes_necessidade_especial : null) == 'S' ? 'selected' : '' }}>Sim</option>
</select>
            @if ($errors->has('pes_necessidade_especial')) <p class="help-block">{{ $errors->first('pes_necessidade_especial') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3" @if ($errors->has('pes_estrangeiro')) has-error @endif>
        <label for="pes_estrangeiro" class="form-label">Estrangeiro?</label>
        <div class="controls">
            <select name="pes_estrangeiro" class="form-control">
    <option value="0" {{ old('pes_estrangeiro', isset($pessoa->pes_estrangeiro) ? $pessoa->pes_estrangeiro : null) == '0' ? 'selected' : '' }}>Não</option>
    <option value="1" {{ old('pes_estrangeiro', isset($pessoa->pes_estrangeiro) ? $pessoa->pes_estrangeiro : null) == '1' ? 'selected' : '' }}>Sim</option>
</select>
            @if ($errors->has('pes_estrangeiro')) <p class="help-block">{{ $errors->first('pes_estrangeiro') }}</p> @endif
        </div>
    </div>
</div>

<hr class="my-3">
<h3 class="card-title w-100 pb-3">
    <span style="font-weight: bold;"><i class="fa-solid fa-caret-right"></i> Endereço</span>
</h3>

<div class="row">
    <div class="form-group col-md-2 @if ($errors->has('pes_cep')) has-error @endif">
        <label for="pes_cep" class="form-label">CEP <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="text" name="pes_cep" value="{{ old('pes_cep', isset($pessoa->pes_cep) ? $pessoa->pes_cep : null) }}" class="form-control" >
            @if ($errors->has('pes_cep')) <p class="help-block">{{ $errors->first('pes_cep') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('pes_endereco')) has-error @endif">
        <label for="pes_endereco" class="form-label">Endereço <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="text" name="pes_endereco" value="{{ old('pes_endereco', isset($pessoa->pes_endereco) ? $pessoa->pes_endereco : null) }}" class="form-control" >
            @if ($errors->has('pes_endereco')) <p class="help-block">{{ $errors->first('pes_endereco') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('pes_complemento')) has-error @endif">
        <label for="pes_complemento" class="form-label">Complemento</label>
        <div class="controls">
            <input type="text" name="pes_complemento" value="{{ old('pes_complemento', isset($pessoa->pes_complemento) ? $pessoa->pes_complemento : null) }}" class="form-control" >
            @if ($errors->has('pes_complemento')) <p class="help-block">{{ $errors->first('pes_complemento') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-2 @if ($errors->has('pes_numero')) has-error @endif">
        <label for="pes_numero" class="form-label">Número <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="text" name="pes_numero" value="{{ old('pes_numero', isset($pessoa->pes_numero) ? $pessoa->pes_numero : null) }}" class="form-control" >
            @if ($errors->has('pes_numero')) <p class="help-block">{{ $errors->first('pes_numero') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('pes_bairro')) has-error @endif">
        <label for="pes_bairro" class="form-label">Bairro <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="text" name="pes_bairro" value="{{ old('pes_bairro', isset($pessoa->pes_bairro) ? $pessoa->pes_bairro : null) }}" class="form-control" >
            @if ($errors->has('pes_bairro')) <p class="help-block">{{ $errors->first('pes_bairro') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('pes_cidade')) has-error @endif">
        <label for="pes_cidade" class="form-label">Cidade <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="text" name="pes_cidade" value="{{ old('pes_cidade', isset($pessoa->pes_cidade) ? $pessoa->pes_cidade : null) }}" class="form-control" >
            @if ($errors->has('pes_cidade')) <p class="help-block">{{ $errors->first('pes_cidade') }}</p> @endif
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group @if ($errors->has('pes_estado')) has-error @endif">
            <label for="pes_estado">Estado <small class="obrigatorio-dot">*</small></label>
            <select name="pes_estado" class="form-control">
    <option value="">Selecione uma opção...</option>
    <option value="AC" {{ old('pes_estado', isset($pessoa->pes_estado) ? $pessoa->pes_estado : null) == 'AC' ? 'selected' : '' }}>Acre</option>
    <option value="AL" {{ old('pes_estado', isset($pessoa->pes_estado) ? $pessoa->pes_estado : null) == 'AL' ? 'selected' : '' }}>Alagoas</option>
    <option value="AP" {{ old('pes_estado', isset($pessoa->pes_estado) ? $pessoa->pes_estado : null) == 'AP' ? 'selected' : '' }}>Amapá</option>
    <option value="AM" {{ old('pes_estado', isset($pessoa->pes_estado) ? $pessoa->pes_estado : null) == 'AM' ? 'selected' : '' }}>Amazonas</option>
    <option value="BA" {{ old('pes_estado', isset($pessoa->pes_estado) ? $pessoa->pes_estado : null) == 'BA' ? 'selected' : '' }}>Bahia</option>
    <option value="CE" {{ old('pes_estado', isset($pessoa->pes_estado) ? $pessoa->pes_estado : null) == 'CE' ? 'selected' : '' }}>Ceará</option>
    <option value="DF" {{ old('pes_estado', isset($pessoa->pes_estado) ? $pessoa->pes_estado : null) == 'DF' ? 'selected' : '' }}>Distrito Federal</option>
    <option value="ES" {{ old('pes_estado', isset($pessoa->pes_estado) ? $pessoa->pes_estado : null) == 'ES' ? 'selected' : '' }}>Espirito Santo</option>
    <option value="GO" {{ old('pes_estado', isset($pessoa->pes_estado) ? $pessoa->pes_estado : null) == 'GO' ? 'selected' : '' }}>Goiás</option>
    <option value="MA" {{ old('pes_estado', isset($pessoa->pes_estado) ? $pessoa->pes_estado : null) == 'MA' ? 'selected' : '' }}>Maranhão</option>
    <option value="MT" {{ old('pes_estado', isset($pessoa->pes_estado) ? $pessoa->pes_estado : null) == 'MT' ? 'selected' : '' }}>Mato Grosso</option>
    <option value="MS" {{ old('pes_estado', isset($pessoa->pes_estado) ? $pessoa->pes_estado : null) == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
    <option value="MG" {{ old('pes_estado', isset($pessoa->pes_estado) ? $pessoa->pes_estado : null) == 'MG' ? 'selected' : '' }}>Minas Gerais</option>
    <option value="PA" {{ old('pes_estado', isset($pessoa->pes_estado) ? $pessoa->pes_estado : null) == 'PA' ? 'selected' : '' }}>Pará</option>
    <option value="PB" {{ old('pes_estado', isset($pessoa->pes_estado) ? $pessoa->pes_estado : null) == 'PB' ? 'selected' : '' }}>Paraiba</option>
    <option value="PR" {{ old('pes_estado', isset($pessoa->pes_estado) ? $pessoa->pes_estado : null) == 'PR' ? 'selected' : '' }}>Paraná</option>
    <option value="PE" {{ old('pes_estado', isset($pessoa->pes_estado) ? $pessoa->pes_estado : null) == 'PE' ? 'selected' : '' }}>Pernambuco</option>
    <option value="PI" {{ old('pes_estado', isset($pessoa->pes_estado) ? $pessoa->pes_estado : null) == 'PI' ? 'selected' : '' }}>Piauí</option>
    <option value="RJ" {{ old('pes_estado', isset($pessoa->pes_estado) ? $pessoa->pes_estado : null) == 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
    <option value="RN" {{ old('pes_estado', isset($pessoa->pes_estado) ? $pessoa->pes_estado : null) == 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
    <option value="RS" {{ old('pes_estado', isset($pessoa->pes_estado) ? $pessoa->pes_estado : null) == 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
    <option value="RO" {{ old('pes_estado', isset($pessoa->pes_estado) ? $pessoa->pes_estado : null) == 'RO' ? 'selected' : '' }}>Rondônia</option>
    <option value="RR" {{ old('pes_estado', isset($pessoa->pes_estado) ? $pessoa->pes_estado : null) == 'RR' ? 'selected' : '' }}>Roraima</option>
    <option value="SC" {{ old('pes_estado', isset($pessoa->pes_estado) ? $pessoa->pes_estado : null) == 'SC' ? 'selected' : '' }}>Santa Catarina</option>
    <option value="SP" {{ old('pes_estado', isset($pessoa->pes_estado) ? $pessoa->pes_estado : null) == 'SP' ? 'selected' : '' }}>São Paulo</option>
    <option value="SE" {{ old('pes_estado', isset($pessoa->pes_estado) ? $pessoa->pes_estado : null) == 'SE' ? 'selected' : '' }}>Sergipe</option>
    <option value="TO" {{ old('pes_estado', isset($pessoa->pes_estado) ? $pessoa->pes_estado : null) == 'TO' ? 'selected' : '' }}>Tocantis</option>
</select>
            @if ($errors->has('pes_estado')) <p class="help-block">{{ $errors->first('pes_estado') }}</p> @endif
        </div>
    </div>
</div>

@section('scripts')
    <script src="{{ asset('/js/plugins/input-mask/inputmask.js') }}"></script>
    <script src="{{ asset('/js/plugins/input-mask/inputmask.date.extensions.js') }}"></script>
    <script src="{{ asset('/js/plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('/js/plugins/bootstrap-datepicker.js') }}"></script>
    <script src="{{asset('/js/plugins/bootstrap-datepicker.pt-BR.js')}}"></script>
    <script src="{{ asset('/js/plugins/cpfcnpj.min.js') }}"></script>

    <script>

        $(function (){

            $('.datepicker').datepicker({
                format: "dd/mm/yyyy",
                language: 'pt-BR',
                autoclose: true
            });

            $('#doc_conteudo').inputmask({"mask": "999.999.999-99", "removeMaskOnSubmit": true});
            $('#pes_telefone').inputmask({"mask": "(99) 99999-9999", "removeMaskOnSubmit": true});
            $('#pes_cep').inputmask({"mask": "99999-999", "removeMaskOnSubmit": true});

            $("#pes_cep").focusout(function(e){

                function limpaFormCep() {

                    $("#pes_cidade").val("");
                    $("#pes_estado").val("");
                    $("#pes_bairro").val("");
                    $("#pes_endereco").val("");
                }

                var str = e.target.value;

                var cep = str.replace(/\D/g, '');

                if (str != "") {
                    //Expressão regular para validar o CEP.
                    var validacep = /^[0-9]{8}$/;

                    if(validacep.test(cep)) {

                        $("#pes_cidade").val("Buscando...");
                        $("#pes_estado").val("Buscando...");
                        $("#pes_bairro").val("Buscando...");
                        $("#pes_endereco").val("Buscando...");

                        $.harpia.httpget('https://viacep.com.br/ws/' + cep + '/json/').done(function (data) {
                            if (!data.erro) {
                                $("#pes_cidade").val(data.localidade);
                                $("#pes_estado").val(data.uf).change();
                                $("#pes_bairro").val(data.bairro);
                                $("#pes_endereco").val(data.logradouro);
                            } else {
                                limpaFormCep();
                                toastr.error("CEP não encontrado", null, {progressBar: true});
                            }
                        });
                    } else {
                        limpaFormCep();
                        toastr.warning("Formato do CEP inválido", null, {progressBar: true});
                    }
                } else {
                    limpaFormCep();
                }
            });
        });
    </script>
@endsection
