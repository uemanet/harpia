@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('/css/plugins/datepicker3.css') }}">
@stop

<div class="row">
    @if(isset($pessoa->pes_id))
        {!! Form::hidden('pes_id', $pessoa->pes_id) !!}
    @endif
    <div class="form-group col-md-4 @if ($errors->has('pes_nome')) has-error @endif">
        {!! Form::label('pes_nome', 'Nome completo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pes_nome', isset($pessoa->pes_nome) ? $pessoa->pes_nome : old('pes_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_nome')) <p class="help-block">{{ $errors->first('pes_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('pes_email')) has-error @endif">
        {!! Form::label('pes_email', 'Email*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::email('pes_email', isset($pessoa->pes_email) ? $pessoa->pes_email : old('pes_email'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_email')) <p class="help-block">{{ $errors->first('pes_email') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('doc_conteudo')) has-error @endif">
        {!! Form::label('doc_conteudo', 'CPF*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('doc_conteudo', isset($pessoa->doc_conteudo) ? $pessoa->doc_conteudo : old('doc_conteudo'), ['class' => 'form-control']) !!}
            @if ($errors->has('doc_conteudo')) <p class="help-block">{{ $errors->first('doc_conteudo') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('pes_mae')) has-error @endif">
        {!! Form::label('pes_mae', 'Nome da mãe*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pes_mae', isset($pessoa->pes_mae) ? $pessoa->pes_mae : old('pes_mae'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_mae')) <p class="help-block">{{ $errors->first('pes_mae') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('pes_pai')) has-error @endif">
        {!! Form::label('pes_pai', 'Nome do pai', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pes_pai', isset($pessoa->pes_pai) ? $pessoa->pes_pai : old('pes_pai'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_pai')) <p class="help-block">{{ $errors->first('pes_pai') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-2 @if ($errors->has('pes_sexo')) has-error @endif">
        {!! Form::label('pes_sexo', 'Sexo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('pes_sexo', ['M' => 'Masculino', 'F' => 'Feminino'], isset($pessoa->pes_sexo) ? $pessoa->pes_sexo : old('pes_sexo'), ['class' => 'form-control', 'placeholder' => 'Selecione o sexo']) !!}
            @if ($errors->has('pes_sexo')) <p class="help-block">{{ $errors->first('pes_sexo') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('pes_telefone')) has-error @endif">
        {!! Form::label('pes_telefone', 'Telefone*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pes_telefone', isset($pessoa->pes_telefone) ? $pessoa->pes_telefone : old('pes_telefone'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_telefone')) <p class="help-block">{{ $errors->first('pes_telefone') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 @if ($errors->has('pes_nascimento')) has-error @endif">
        {!! Form::label('pes_nascimento', 'Nascimento*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pes_nascimento', isset($pessoa->pes_nascimento) ? $pessoa->pes_nascimento : old('pes_nascimento'), ['class' => 'form-control datepicker']) !!}
            @if ($errors->has('pes_nascimento')) <p class="help-block">{{ $errors->first('pes_nascimento') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 @if ($errors->has('pes_estado_civil')) has-error @endif">
        {!! Form::label('pes_estado_civil', 'Estado civil*', ['class' => 'control-label']) !!}

        <div class="controls">
            {!! Form::select('pes_estado_civil',
                                ["solteiro" => "Solteiro(a)",
                                  "casado" => "Casado(a)",
                                  "divorciado" => "Divorciado(a)",
                                  "uniao_estavel" => "União estável",
                                  "viuvo" => "Viúvo(a)",
                                  "outro" => "Outro"],
                                 isset($pessoa->pes_estado_civil) ? $pessoa->pes_estado_civil : old('pes_estado_civil'),
                                 ['class' => 'form-control', 'placeholder' => 'Selecione o estado civil']) !!}
            @if ($errors->has('pes_estado_civil')) <p class="help-block">{{ $errors->first('pes_estado_civil') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('pes_raca')) has-error @endif">
        {!! Form::label('pes_raca', 'Cor/Raça*', ['class' => 'control-label']) !!}

        <div class="controls">
            {!! Form::select('pes_raca',
                        ["branca" => "Branca",
                          "preta" => "Preta",
                          "parda" => "Parda",
                          "amarela" => "Amarela",
                          "indigena" => "Indígena",
                          "outra" => "Outra"],
                         isset($pessoa->pes_raca) ? $pessoa->pes_raca : old('pes_raca'),
                         ['class' => 'form-control', 'placeholder' => 'Selecione a raça']) !!}
            @if ($errors->has('pes_raca')) <p class="help-block">{{ $errors->first('pes_raca') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-3 @if ($errors->has('pes_naturalidade')) has-error @endif">
        {!! Form::label('pes_naturalidade', 'Naturalidade*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pes_naturalidade', isset($pessoa->pes_naturalidade) ? $pessoa->pes_naturalidade : old('pes_naturalidade'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_naturalidade')) <p class="help-block">{{ $errors->first('pes_naturalidade') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('pes_nacionalidade')) has-error @endif">
        {!! Form::label('pes_nacionalidade', 'Nacionalidade*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pes_nacionalidade', isset($pessoa->pes_nacionalidade) ? $pessoa->pes_nacionalidade : old('pes_nacionalidade'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_nacionalidade')) <p class="help-block">{{ $errors->first('pes_nacionalidade') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('pes_necessidade_especial')) has-error @endif">
        {!! Form::label('pes_necessidade_especial', 'Necessidade especial?*', ['class' => 'control-label']) !!}

        <div class="controls">
            {!! Form::select('pes_necessidade_especial', ['N' => 'Não', 'S' => 'Sim'], isset($pessoa->pes_necessidade_especial) ? $pessoa->pes_necessidade_especial : old('pes_necessidade_especial'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_necessidade_especial')) <p class="help-block">{{ $errors->first('pes_necessidade_especial') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3" @if ($errors->has('pes_estrangeiro')) has-error @endif>
        {!! Form::label('pes_estrangeiro', 'Estrangeiro?*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('pes_estrangeiro', ['0' => 'Não', '1' => 'Sim'], isset($pessoa->pes_estrangeiro) ? $pessoa->pes_estrangeiro : old('pes_estrangeiro'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_estrangeiro')) <p class="help-block">{{ $errors->first('pes_estrangeiro') }}</p> @endif
        </div>
    </div>
</div>

<h4 class="box-title">
    Endereço
</h4>

<div class="row">
    <div class="form-group col-md-2 @if ($errors->has('pes_cep')) has-error @endif">
        {!! Form::label('pes_cep', 'CEP*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pes_cep', isset($pessoa->pes_cep) ? $pessoa->pes_cep : old('pes_cep'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_cep')) <p class="help-block">{{ $errors->first('pes_cep') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('pes_endereco')) has-error @endif">
        {!! Form::label('pes_endereco', 'Endereço*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pes_endereco', isset($pessoa->pes_endereco) ? $pessoa->pes_endereco : old('pes_endereco'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_endereco')) <p class="help-block">{{ $errors->first('pes_endereco') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('pes_complemento')) has-error @endif">
        {!! Form::label('pes_complemento', 'Complemento', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pes_complemento', isset($pessoa->pes_complemento) ? $pessoa->pes_complemento : old('pes_complemento'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_complemento')) <p class="help-block">{{ $errors->first('pes_complemento') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-2 @if ($errors->has('pes_numero')) has-error @endif">
        {!! Form::label('pes_numero', 'Número*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pes_numero', isset($pessoa->pes_numero) ? $pessoa->pes_numero : old('pes_numero'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_numero')) <p class="help-block">{{ $errors->first('pes_numero') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('pes_bairro')) has-error @endif">
        {!! Form::label('pes_bairro', 'Bairro*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pes_bairro', isset($pessoa->pes_bairro) ? $pessoa->pes_bairro : old('pes_bairro'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_bairro')) <p class="help-block">{{ $errors->first('pes_bairro') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('pes_cidade')) has-error @endif">
        {!! Form::label('pes_cidade', 'Cidade*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pes_cidade', isset($pessoa->pes_cidade) ? $pessoa->pes_cidade : old('pes_cidade'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_cidade')) <p class="help-block">{{ $errors->first('pes_cidade') }}</p> @endif
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group @if ($errors->has('pes_estado')) has-error @endif">
            {!! Form::label('pes_estado', 'Estado*') !!}
            {!! Form::select('pes_estado', [
                    'AC' => 'Acre',
                    'AL' => 'Alagoas',
                    'AP' => 'Amapá',
                    'AM' => 'Amazonas',
                    'BA' => 'Bahia',
                    'CE' => 'Ceará',
                    'DF' => 'Distrito Federal',
                    'ES' => 'Espirito Santo',
                    'GO' => 'Goiás',
                    'MA' => 'Maranhão',
                    'MT' => 'Mato Grosso',
                    'MS' => 'Mato Grosso do Sul',
                    'MG' => 'Minas Gerais',
                    'PA' => 'Pará',
                    'PB' => 'Paraiba',
                    'PR' => 'Paraná',
                    'PE' => 'Pernambuco',
                    'PI' => 'Piauí',
                    'RJ' => 'Rio de Janeiro',
                    'RN' => 'Rio Grande do Norte',
                    'RS' => 'Rio Grande do Sul',
                    'RO' => 'Rondônia',
                    'RR' => 'Roraima',
                    'SC' => 'Santa Catarina',
                    'SP' => 'São Paulo',
                    'SE' => 'Sergipe',
                    'TO' => 'Tocantis',
                ], old('pes_estado'), ['class' => 'form-control', 'placeholder' => 'Selecione uma opção...', 'required' => 'required']) !!}
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
                var str = e.target.value;

                var cep = str.replace(/\D/g, '');

                $.harpia.httpget('https://viacep.com.br/ws/'+cep+'/json/').done(function(data) {
                    if(!data.erro) {
                        $("#pes_cidade").val(data.localidade);
                        $("#pes_estado").val(data.uf);
                        $("#pes_bairro").val(data.bairro);
                        $("#pes_endereco").val(data.logradouro);
                    } else {
                        $("#pes_cidade").val('');
                        $("#pes_estado").val('');
                        $("#pes_bairro").val('');
                        $("#pes_endereco").val('');
                    }
                });

            });
        });
    </script>
@endsection