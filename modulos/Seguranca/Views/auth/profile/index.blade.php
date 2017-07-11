@extends('layouts.clean')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('/css/plugins/datepicker3.css') }}">
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@stop

@section('title')
    Módulo de Segurança
@stop

@section('subtitle')
    Perfil do usuário
@stop

@section('content')
<div class="container" style="padding-top: 20px;">
    <div class="row">
        <div class="col-md-3">

            <!-- Profile Image -->
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="{{url('/')}}/img/avatar.png" alt="User profile picture">

                    <h3 class="profile-username text-center">{{$usuario->pessoa->pes_nome}}</h3>

                    <p class="text-muted text-center">{{$usuario->pessoa->pes_email}}</p>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#dados" data-toggle="tab">Dados pessoais</a></li>
                    <li><a href="#endereco" data-toggle="tab">Endereço</a></li>
                    <li><a href="#senha" data-toggle="tab">Alterar Senha</a></li>
                </ul>
                <div class="tab-content">

                    <div class="active tab-pane" id="dados">
                        {!! Form::model($usuario->pessoa,["route" => ['seguranca.profile.edit'], "method" => "PUT", "id" => "form", "role" => "form", "class" => "form-horizontal"]) !!}
                            <div class="form-group @if ($errors->has('pes_nome')) has-error @endif">
                                {!! Form::label('pes_nome', 'Nome completo*', ['class' => 'col-sm-3 control-label']) !!}

                                <div class="col-sm-9">
                                    {!! Form::text('pes_nome', old('pes_nome'), ['class' => 'form-control']) !!}
                                    @if ($errors->has('pes_nome')) <p class="help-block">{{ $errors->first('pes_nome') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_email')) has-error @endif">
                                {!! Form::label('pes_email', 'Email*', ['class' => 'col-sm-3 control-label']) !!}

                                <div class="col-sm-9">
                                    {!! Form::email('pes_email', old('pes_email'), ['class' => 'form-control']) !!}
                                    @if ($errors->has('pes_email')) <p class="help-block">{{ $errors->first('pes_email') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_telefone')) has-error @endif">
                                {!! Form::label('pes_telefone', 'Telefone*', ['class' => 'col-sm-3 control-label']) !!}

                                <div class="col-sm-9">
                                    {!! Form::text('pes_telefone', old('pes_telefone'), ['class' => 'form-control']) !!}
                                    @if ($errors->has('pes_telefone')) <p class="help-block">{{ $errors->first('pes_telefone') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_sexo')) has-error @endif">
                                {!! Form::label('pes_sexo', 'Sexo*', ['class' => 'col-sm-3 control-label']) !!}

                                <div class="col-sm-9">
                                    {!! Form::select('pes_sexo', ['M' => 'Masculino', 'F' => 'Feminino'], old('pes_sexo'), ['class' => 'form-control']) !!}
                                    @if ($errors->has('pes_sexo')) <p class="help-block">{{ $errors->first('pes_sexo') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_nascimento')) has-error @endif">
                                {!! Form::label('pes_nascimento', 'Nascimento*', ['class' => 'col-sm-3 control-label']) !!}

                                <div class="col-sm-9">
                                    {!! Form::text('pes_nascimento', old('pes_nascimento'), ['class' => 'form-control datepicker']) !!}
                                    @if ($errors->has('pes_nascimento')) <p class="help-block">{{ $errors->first('pes_nascimento') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_estado_civil')) has-error @endif">
                                {!! Form::label('pes_estado_civil', 'Estado Civil*', ['class' => 'col-sm-3 control-label']) !!}

                                <div class="col-sm-9">
                                    {!! Form::select('pes_estado_civil',
                                                    ["solteiro" => "Solteiro(a)",
                                                      "casado" => "Casado(a)",
                                                      "divorciado" => "Divorciado(a)",
                                                      "uniao_estavel" => "União estável",
                                                      "viuvo" => "Viúvo(a)",
                                                      "outro" => "Outro"],
                                                     old('pes_estado_civil'),
                                                     ['class' => 'form-control']) !!}
                                    @if ($errors->has('pes_estado_civil')) <p class="help-block">{{ $errors->first('pes_estado_civil') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_mae')) has-error @endif">
                                {!! Form::label('pes_mae', 'Nome da mãe*', ['class' => 'col-sm-3 control-label']) !!}

                                <div class="col-sm-9">
                                    {!! Form::text('pes_mae', old('pes_mae'), ['class' => 'form-control']) !!}
                                    @if ($errors->has('pes_mae')) <p class="help-block">{{ $errors->first('pes_mae') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_pai')) has-error @endif">
                                {!! Form::label('pes_pai', 'Nome do pai', ['class' => 'col-sm-3 control-label']) !!}

                                <div class="col-sm-9">
                                    {!! Form::text('pes_pai', old('pes_pai'), ['class' => 'form-control']) !!}
                                    @if ($errors->has('pes_pai')) <p class="help-block">{{ $errors->first('pes_pai') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_naturalidade')) has-error @endif">
                                {!! Form::label('pes_naturalidade', 'Naturalidade*', ['class' => 'col-sm-3 control-label']) !!}

                                <div class="col-sm-9">
                                    {!! Form::text('pes_naturalidade', old('pes_naturalidade'), ['class' => 'form-control']) !!}
                                    @if ($errors->has('pes_naturalidade')) <p class="help-block">{{ $errors->first('pes_naturalidade') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_nacionalidade')) has-error @endif">
                                {!! Form::label('pes_nacionalidade', 'Nacionalidade*', ['class' => 'col-sm-3 control-label']) !!}

                                <div class="col-sm-9">
                                    {!! Form::text('pes_nacionalidade', old('pes_nacionalidade'), ['class' => 'form-control']) !!}
                                    @if ($errors->has('pes_nacionalidade')) <p class="help-block">{{ $errors->first('pes_nacionalidade') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_raca')) has-error @endif">
                                {!! Form::label('pes_raca', 'Cor/Raça*', ['class' => 'col-sm-3 control-label']) !!}

                                <div class="col-sm-9">
                                    {!! Form::select('pes_raca',
                                                    ["branca" => "Branca",
                                                      "preta" => "Preta",
                                                      "parda" => "Parda",
                                                      "amarela" => "Amarela",
                                                      "indigena" => "Indígena",
                                                      "outra" => "Outra"],
                                                     old('pes_raca'),
                                                     ['class' => 'form-control']) !!}
                                    @if ($errors->has('pes_raca')) <p class="help-block">{{ $errors->first('pes_raca') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_necessidade_especial')) has-error @endif">
                                {!! Form::label('pes_necessidade_especial', 'Necessidade especial?*', ['class' => 'col-sm-3 control-label']) !!}

                                <div class="col-sm-9">
                                    {!! Form::select('pes_necessidade_especial', ['S' => 'Sim', 'N' => 'Não'], old('pes_necessidade_especial'), ['class' => 'form-control']) !!}
                                    @if ($errors->has('pes_necessidade_especial')) <p class="help-block">{{ $errors->first('pes_necessidade_especial') }}</p> @endif
                                </div>
                            </div>

                            <div class="form-group @if ($errors->has('pes_estrangeiro')) has-error @endif">
                            {!! Form::label('pes_estrangeiro', 'Estrangeiro?*', ['class' => 'col-sm-3 control-label']) !!}

                            <div class="col-sm-9">
                                {!! Form::select('pes_estrangeiro', ['0' => 'Não', '1' => 'Sim'], old('pes_estrangeiro'), ['class' => 'form-control']) !!}
                                @if ($errors->has('pes_estrangeiro')) <p class="help-block">{{ $errors->first('pes_estrangeiro') }}</p> @endif
                            </div>
                        </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary">Atualizar informações</button>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>

                    <div class="tab-pane" id="endereco">
                        {!! Form::model($usuario->pessoa, ['route' => 'seguranca.profile.edit', 'method' => 'POST','id' => 'form', 'role' => 'form', 'class' => 'form-horizontal']) !!}
                            <div class="form-group @if ($errors->has('pes_cep')) has-error @endif">
                                {!! Form::label('pes_cep', 'CEP*', ['class' => 'col-sm-3 control-label']) !!}

                                <div class="col-sm-9">
                                    {!! Form::text('pes_cep', old('pes_cep'), ['class' => 'form-control']) !!}
                                    @if ($errors->has('pes_cep')) <p class="help-block">{{ $errors->first('pes_cep') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_endereco')) has-error @endif">
                                {!! Form::label('pes_endereco', 'Endereço*', ['class' => 'col-sm-3 control-label']) !!}

                                <div class="col-sm-9">
                                    {!! Form::text('pes_endereco', old('pes_endereco'), ['class' => 'form-control']) !!}
                                    @if ($errors->has('pes_endereco')) <p class="help-block">{{ $errors->first('pes_endereco') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_complemento')) has-error @endif">
                                {!! Form::label('pes_complemento', 'Complemento', ['class' => 'col-sm-3 control-label']) !!}

                                <div class="col-sm-9">
                                    {!! Form::text('pes_complemento', old('pes_complemento'), ['class' => 'form-control']) !!}
                                    @if ($errors->has('pes_complemento')) <p class="help-block">{{ $errors->first('pes_complemento') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_numero')) has-error @endif">
                                {!! Form::label('pes_numero', 'Numero*', ['class' => 'col-sm-3 control-label']) !!}

                                <div class="col-sm-9">
                                    {!! Form::text('pes_numero', old('pes_numero'), ['class' => 'form-control']) !!}
                                    @if ($errors->has('pes_numero')) <p class="help-block">{{ $errors->first('pes_numero') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_bairro')) has-error @endif">
                                {!! Form::label('pes_bairro', 'Bairro*', ['class' => 'col-sm-3 control-label']) !!}

                                <div class="col-sm-9">
                                    {!! Form::text('pes_bairro', old('pes_bairro'), ['class' => 'form-control']) !!}
                                    @if ($errors->has('pes_bairro')) <p class="help-block">{{ $errors->first('pes_bairro') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_cidade')) has-error @endif">
                                {!! Form::label('pes_cidade', 'Cidade*', ['class' => 'col-sm-3 control-label']) !!}

                                <div class="col-sm-9">
                                    {!! Form::text('pes_cidade', old('pes_cidade'), ['class' => 'form-control']) !!}
                                    @if ($errors->has('pes_cidade')) <p class="help-block">{{ $errors->first('pes_cidade') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_estado')) has-error @endif">
                            {!! Form::label('pes_estado', 'Estado*', ['class' => 'col-sm-3 control-label']) !!}
                            @php
                                $estados = ['AC' => 'Acre',
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
                                ];
                            @endphp
                            <div class="col-sm-9">
                                {!! Form::select('pes_estado',$estados, old('pes_estado'), ['class' => 'form-control', 'placeholder' => 'Selecione um estado']) !!}
                                @if ($errors->has('pes_estado')) <p class="help-block">{{ $errors->first('pes_estado') }}</p> @endif
                            </div>
                        </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary">Atualizar Endereço</button>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                    <!-- /.tab-pane -->

                    <div class="tab-pane" id="senha">
                        {!! Form::model($usuario->pessoa,["route" => "seguranca.profile.updatepassword", "method" => "PUT", "id" => "form", "role" => "form", "class" => "form-horizontal"]) !!}
                            <div class="form-group @if ($errors->has('usr_senha')) has-error @endif">
                                {!! Form::label('usr_senha', 'Senha atual*', ['class' => 'col-sm-3 control-label']) !!}

                                <div class="col-sm-9">
                                    {!! Form::password('usr_senha', ['class' => 'form-control']) !!}
                                    @if ($errors->has('usr_senha')) <p class="help-block">{{ $errors->first('usr_senha') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('usr_senha_nova')) has-error @endif">
                                {!! Form::label('usr_senha_nova', 'Nova senha*', ['class' => 'col-sm-3 control-label']) !!}

                                <div class="col-sm-9">
                                    {!! Form::password('usr_senha_nova', ['class' => 'form-control']) !!}
                                    @if ($errors->has('usr_senha_nova')) <p class="help-block">{{ $errors->first('usr_senha_nova') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('usr_senha_nova_confirmation')) has-error @endif">
                                {!! Form::label('usr_senha_nova_confirmation', 'Repita a nova senha*', ['class' => 'col-sm-3 control-label']) !!}

                                <div class="col-sm-9">
                                    {!! Form::password('usr_senha_nova_confirmation', ['class' => 'form-control']) !!}
                                    @if ($errors->has('usr_senha_nova_confirmation')) <p class="help-block">{{ $errors->first('usr_senha_nova_confirmation') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-danger">Alterar senha</button>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</div>
<!-- /.container -->
@stop

@section('scripts')
    <script src="{{ asset('/js/plugins/input-mask/inputmask.js') }}"></script>
    <script src="{{ asset('/js/plugins/input-mask/inputmask.date.extensions.js') }}"></script>
    <script src="{{ asset('/js/plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{ asset('/js/plugins/bootstrap-datepicker.js') }}"></script>
    <script src="{{asset('/js/plugins/bootstrap-datepicker.pt-BR.js')}}"></script>
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        $("select").select2();

        $('.datepicker').datepicker({
            format: "dd/mm/yyyy",
            language: 'pt-BR',
            autoclose: true
        });

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
    </script>
@stop