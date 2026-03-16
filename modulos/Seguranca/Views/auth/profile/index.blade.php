@extends('layouts.clean')

@section('title')
    Módulo de Segurança
@stop

@section('subtitle')
    Perfil do usuário
@stop

@section('content')

    <div class="content-wrapper p-5">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <!-- Profile Image -->
                        <div class="card card-primary card-outline">
                            <div class="card-body box-profile">
                                <div class="text-center">
                                    <img class="profile-user-img img-circle"
                                         style="border-radius: 50%;"
                                         src="{{ route('seguranca.profile.profile-picture', \Illuminate\Support\Facades\Auth::user()->usr_profile_picture_id ?? 0) }}"
                                         alt="User profile picture">
                                </div>

                                <h3 class="profile-username text-center">{{$usuario->pessoa->pes_nome}}</h3>
                                <p class="text-muted text-center">{{$usuario->pessoa->pes_email}}</p>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>

                    <div class="col-md-9">
                        <div class="card card-primary card-outline">
                            <div class="card-header p-2">
                                <ul class="nav nav-pills">
                                    <li class="nav-item">
                                        <a class="nav-link @if (!$errors->has('usr_senha') and !$errors->has('usr_senha_nova') and !$errors->has('usr_senha_nova_confirmation')) active @endif"
                                           href="#dados"
                                           data-bs-toggle="tab"
                                        >
                                            Dados pessoais
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                           href="#endereco"
                                           data-bs-toggle="tab"
                                        >
                                            Endereço
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link @if ($errors->has('usr_senha') or $errors->has('usr_senha_nova') or $errors->has('usr_senha_nova_confirmation')) active @endif"
                                           href="#senha"
                                           data-bs-toggle="tab"
                                        >
                                            Alterar Senha
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link"
                                           href="#foto"
                                           data-bs-toggle="tab"
                                        >
                                            Alterar Foto
                                        </a>
                                    </li>
                                </ul>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane @if (!$errors->has('usr_senha') and !$errors->has('usr_senha_nova') and !$errors->has('usr_senha_nova_confirmation')) active @endif" id="dados">
                                        {!! Form::model($usuario->pessoa,["route" => ['seguranca.profile.edit'], "method" => "PUT", "id" => "form", "role" => "form", "class" => "form-horizontal"]) !!}
                                        <div class="form-group @if ($errors->has('pes_nome')) has-error @endif">
                                            {!! Form::label('pes_nome', 'Nome completo*', ['class' => 'col-sm-3 control-label']) !!}

                                            <div class="col-sm-9">
                                                @haspermission('seguranca.profile.edit')
                                                {!! Form::text('pes_nome', old('pes_nome'), ['class' => 'form-control']) !!}
                                                @else
                                                    {!! Form::text('pes_nome', old('pes_nome'), ['disabled','class' => 'form-control']) !!}
                                                @endif
                                                @if ($errors->has('pes_nome')) <p
                                                        class="help-block">{{ $errors->first('pes_nome') }}</p> @endif
                                            </div>
                                        </div>
                                        <div class="form-group @if ($errors->has('pes_email')) has-error @endif">
                                            {!! Form::label('pes_email', 'Email*', ['class' => 'col-sm-3 control-label']) !!}
                                            <div class="col-sm-9">
                                                @haspermission('seguranca.profile.edit')
                                                {!! Form::email('pes_email', old('pes_email'), ['class' => 'form-control']) !!}
                                                @else
                                                    {!! Form::email('pes_email', old('pes_email'), ['disabled' ,'class' => 'form-control']) !!}
                                                @endif
                                                @if ($errors->has('pes_email')) <p
                                                        class="help-block">{{ $errors->first('pes_email') }}</p> @endif
                                            </div>
                                        </div>
                                        <div class="form-group @if ($errors->has('pes_telefone')) has-error @endif">
                                            {!! Form::label('pes_telefone', 'Telefone*', ['class' => 'col-sm-3 control-label']) !!}

                                            <div class="col-sm-9">
                                                @haspermission('seguranca.profile.edit')
                                                {!! Form::text('pes_telefone', old('pes_telefone'), ['class' => 'form-control']) !!}
                                                @else
                                                    {!! Form::text('pes_telefone', old('pes_telefone'), ['disabled','class' => 'form-control']) !!}
                                                @endif
                                                @if ($errors->has('pes_telefone')) <p
                                                        class="help-block">{{ $errors->first('pes_telefone') }}</p> @endif
                                            </div>
                                        </div>
                                        <div class="form-group @if ($errors->has('pes_sexo')) has-error @endif">
                                            {!! Form::label('pes_sexo', 'Sexo*', ['class' => 'col-sm-3 control-label']) !!}

                                            <div class="col-sm-9">
                                                @haspermission('seguranca.profile.edit')
                                                {!! Form::select('pes_sexo', ['M' => 'Masculino', 'F' => 'Feminino'], old('pes_sexo'), ['class' => 'form-control']) !!}
                                                @else
                                                    {!! Form::select('pes_sexo', ['M' => 'Masculino', 'F' => 'Feminino'], old('pes_sexo'), ['disabled','class' => 'form-control']) !!}
                                                @endif
                                                @if ($errors->has('pes_sexo')) <p
                                                        class="help-block">{{ $errors->first('pes_sexo') }}</p> @endif
                                            </div>
                                        </div>
                                        <div class="form-group @if ($errors->has('pes_nascimento')) has-error @endif">
                                            {!! Form::label('pes_nascimento', 'Nascimento*', ['class' => 'col-sm-3 control-label']) !!}

                                            <div class="col-sm-9">
                                                @haspermission('seguranca.profile.edit')
                                                {!! Form::text('pes_nascimento', old('pes_nascimento'), ['class' => 'form-control datepicker']) !!}
                                                @else
                                                    {!! Form::text('pes_nascimento', old('pes_nascimento'), ['disabled','class' => 'form-control datepicker']) !!}
                                                @endif
                                                @if ($errors->has('pes_nascimento')) <p
                                                        class="help-block">{{ $errors->first('pes_nascimento') }}</p> @endif
                                            </div>
                                        </div>
                                        <div class="form-group @if ($errors->has('pes_estado_civil')) has-error @endif">
                                            {!! Form::label('pes_estado_civil', 'Estado Civil*', ['class' => 'col-sm-3 control-label']) !!}

                                            <div class="col-sm-9">
                                                @haspermission('seguranca.profile.edit')
                                                {!! Form::select('pes_estado_civil',
                                                                                                ["solteiro" => "Solteiro(a)",
                                                                                                  "casado" => "Casado(a)",
                                                                                                  "divorciado" => "Divorciado(a)",
                                                                                                  "uniao_estavel" => "União estável",
                                                                                                  "viuvo" => "Viúvo(a)",
                                                                                                  "outro" => "Outro"],
                                                                                                 old('pes_estado_civil'),
                                                                                                 ['class' => 'form-control']) !!}
                                                @else
                                                    {!! Form::select('pes_estado_civil',
                                                                                                        ["solteiro" => "Solteiro(a)",
                                                                                                          "casado" => "Casado(a)",
                                                                                                          "divorciado" => "Divorciado(a)",
                                                                                                          "uniao_estavel" => "União estável",
                                                                                                          "viuvo" => "Viúvo(a)",
                                                                                                          "outro" => "Outro"],
                                                                                                         old('pes_estado_civil'),
                                                                                                         ['disabled','class' => 'form-control']) !!}
                                                @endif
                                                @if ($errors->has('pes_estado_civil')) <p
                                                        class="help-block">{{ $errors->first('pes_estado_civil') }}</p> @endif
                                            </div>
                                        </div>
                                        <div class="form-group @if ($errors->has('pes_mae')) has-error @endif">
                                            {!! Form::label('pes_mae', 'Nome da mãe*', ['class' => 'col-sm-3 control-label']) !!}

                                            <div class="col-sm-9">
                                                @haspermission('seguranca.profile.edit')
                                                {!! Form::text('pes_mae', old('pes_mae'), ['class' => 'form-control']) !!}
                                                @else
                                                    {!! Form::text('pes_mae', old('pes_mae'), ['disabled','class' => 'form-control']) !!}
                                                @endif
                                                @if ($errors->has('pes_mae')) <p
                                                        class="help-block">{{ $errors->first('pes_mae') }}</p> @endif
                                            </div>
                                        </div>
                                        <div class="form-group @if ($errors->has('pes_pai')) has-error @endif">
                                            {!! Form::label('pes_pai', 'Nome do pai', ['class' => 'col-sm-3 control-label']) !!}

                                            <div class="col-sm-9">
                                                @haspermission('seguranca.profile.edit')
                                                {!! Form::text('pes_pai', old('pes_pai'), ['class' => 'form-control']) !!}
                                                @else
                                                    {!! Form::text('pes_pai', old('pes_pai'), ['disabled','class' => 'form-control']) !!}
                                                @endif
                                                @if ($errors->has('pes_pai')) <p
                                                        class="help-block">{{ $errors->first('pes_pai') }}</p> @endif
                                            </div>
                                        </div>
                                        <div class="form-group @if ($errors->has('pes_naturalidade')) has-error @endif">
                                            {!! Form::label('pes_naturalidade', 'Naturalidade*', ['class' => 'col-sm-3 control-label']) !!}

                                            <div class="col-sm-9">
                                                @haspermission('seguranca.profile.edit')
                                                {!! Form::text('pes_naturalidade', old('pes_naturalidade'), ['class' => 'form-control']) !!}
                                                @else
                                                    {!! Form::text('pes_naturalidade', old('pes_naturalidade'), ['disabled','class' => 'form-control']) !!}
                                                @endif
                                                @if ($errors->has('pes_naturalidade')) <p
                                                        class="help-block">{{ $errors->first('pes_naturalidade') }}</p> @endif
                                            </div>
                                        </div>
                                        <div class="form-group @if ($errors->has('pes_nacionalidade')) has-error @endif">
                                            {!! Form::label('pes_nacionalidade', 'Nacionalidade*', ['class' => 'col-sm-3 control-label']) !!}

                                            <div class="col-sm-9">
                                                @haspermission('seguranca.profile.edit')
                                                {!! Form::text('pes_nacionalidade', old('pes_nacionalidade'), ['class' => 'form-control']) !!}
                                                @else
                                                    {!! Form::text('pes_nacionalidade', old('pes_nacionalidade'), ['disabled','class' => 'form-control']) !!}
                                                @endif
                                                @if ($errors->has('pes_nacionalidade')) <p
                                                        class="help-block">{{ $errors->first('pes_nacionalidade') }}</p> @endif
                                            </div>
                                        </div>
                                        <div class="form-group @if ($errors->has('pes_raca')) has-error @endif">
                                            {!! Form::label('pes_raca', 'Cor/Raça*', ['class' => 'col-sm-3 control-label']) !!}

                                            <div class="col-sm-9">
                                                @haspermission('seguranca.profile.edit')
                                                {!! Form::select('pes_raca',
                                                            ["branca" => "Branca",
                                                              "preta" => "Preta",
                                                              "parda" => "Parda",
                                                              "amarela" => "Amarela",
                                                              "indigena" => "Indígena",
                                                              "outra" => "Outra"],
                                                             old('pes_raca'),
                                                             ['class' => 'form-control']) !!}
                                                @else
                                                    {!! Form::select('pes_raca',
                                                                    ["branca" => "Branca",
                                                                      "preta" => "Preta",
                                                                      "parda" => "Parda",
                                                                      "amarela" => "Amarela",
                                                                      "indigena" => "Indígena",
                                                                      "outra" => "Outra"],
                                                                     old('pes_raca'),
                                                                     ['disabled','class' => 'form-control']) !!}
                                                @endif
                                                @if ($errors->has('pes_raca')) <p
                                                        class="help-block">{{ $errors->first('pes_raca') }}</p> @endif
                                            </div>
                                        </div>
                                        <div class="form-group @if ($errors->has('pes_necessidade_especial')) has-error @endif">
                                            {!! Form::label('pes_necessidade_especial', 'Necessidade especial?*', ['class' => 'col-sm-3 control-label']) !!}

                                            <div class="col-sm-9">
                                                @haspermission('seguranca.profile.edit')
                                                {!! Form::select('pes_necessidade_especial', ['S' => 'Sim', 'N' => 'Não'], old('pes_necessidade_especial'), ['class' => 'form-control']) !!}
                                                @else
                                                    {!! Form::select('pes_necessidade_especial', ['S' => 'Sim', 'N' => 'Não'], old('pes_necessidade_especial'), ['disabled','class' => 'form-control']) !!}
                                                @endif
                                                @if ($errors->has('pes_necessidade_especial')) <p
                                                        class="help-block">{{ $errors->first('pes_necessidade_especial') }}</p> @endif
                                            </div>
                                        </div>

                                        <div class="form-group @if ($errors->has('pes_estrangeiro')) has-error @endif">
                                            {!! Form::label('pes_estrangeiro', 'Estrangeiro?*', ['class' => 'col-sm-3 control-label']) !!}

                                            <div class="col-sm-9">
                                                @haspermission('seguranca.profile.edit')
                                                {!! Form::select('pes_estrangeiro', ['0' => 'Não', '1' => 'Sim'], old('pes_estrangeiro'), ['class' => 'form-control']) !!}
                                                @else
                                                    {!! Form::select('pes_estrangeiro', ['0' => 'Não', '1' => 'Sim'], old('pes_estrangeiro'), ['disabled','class' => 'form-control']) !!}
                                                @endif

                                                @if ($errors->has('pes_estrangeiro')) <p
                                                        class="help-block">{{ $errors->first('pes_estrangeiro') }}</p> @endif
                                            </div>
                                        </div>
                                        @haspermission('seguranca.profile.edit')
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-primary">Atualizar informações</button>
                                            </div>
                                        </div>
                                        @endhaspermission
                                        {!! Form::close() !!}
                                    </div>

                                    <div class="tab-pane" id="endereco">
                                        {!! Form::model($usuario->pessoa, ['route' => 'seguranca.profile.edit', 'method' => 'POST','id' => 'form', 'role' => 'form', 'class' => 'form-horizontal']) !!}
                                        <div class="form-group @if ($errors->has('pes_cep')) has-error @endif">
                                            {!! Form::label('pes_cep', 'CEP*', ['class' => 'col-sm-3 control-label']) !!}

                                            <div class="col-sm-9">
                                                @haspermission('seguranca.profile.edit')
                                                {!! Form::text('pes_cep', old('pes_cep'), ['class' => 'form-control']) !!}
                                                @else
                                                    {!! Form::text('pes_cep', old('pes_cep'), ['disabled','class' => 'form-control']) !!}
                                                @endif
                                                @if ($errors->has('pes_cep')) <p
                                                        class="help-block">{{ $errors->first('pes_cep') }}</p> @endif
                                            </div>
                                        </div>
                                        <div class="form-group @if ($errors->has('pes_endereco')) has-error @endif">
                                            {!! Form::label('pes_endereco', 'Endereço*', ['class' => 'col-sm-3 control-label']) !!}

                                            <div class="col-sm-9">
                                                @haspermission('seguranca.profile.edit')
                                                {!! Form::text('pes_endereco', old('pes_endereco'), ['class' => 'form-control']) !!}
                                                @else
                                                    {!! Form::text('pes_endereco', old('pes_endereco'), ['disabled','class' => 'form-control']) !!}
                                                @endif
                                                @if ($errors->has('pes_endereco')) <p
                                                        class="help-block">{{ $errors->first('pes_endereco') }}</p> @endif
                                            </div>
                                        </div>
                                        <div class="form-group @if ($errors->has('pes_complemento')) has-error @endif">
                                            {!! Form::label('pes_complemento', 'Complemento', ['class' => 'col-sm-3 control-label']) !!}

                                            <div class="col-sm-9">
                                                @haspermission('seguranca.profile.edit')
                                                {!! Form::text('pes_complemento', old('pes_complemento'), ['class' => 'form-control']) !!}
                                                @else
                                                    {!! Form::text('pes_complemento', old('pes_complemento'), ['disabled','class' => 'form-control']) !!}
                                                @endif
                                                @if ($errors->has('pes_complemento')) <p
                                                        class="help-block">{{ $errors->first('pes_complemento') }}</p> @endif
                                            </div>
                                        </div>
                                        <div class="form-group @if ($errors->has('pes_numero')) has-error @endif">
                                            {!! Form::label('pes_numero', 'Numero*', ['class' => 'col-sm-3 control-label']) !!}

                                            <div class="col-sm-9">
                                                @haspermission('seguranca.profile.edit')
                                                {!! Form::text('pes_numero', old('pes_numero'), ['class' => 'form-control']) !!}
                                                @else
                                                    {!! Form::text('pes_numero', old('pes_numero'), ['disabled','class' => 'form-control']) !!}
                                                @endif
                                                @if ($errors->has('pes_numero')) <p
                                                        class="help-block">{{ $errors->first('pes_numero') }}</p> @endif
                                            </div>
                                        </div>
                                        <div class="form-group @if ($errors->has('pes_bairro')) has-error @endif">
                                            {!! Form::label('pes_bairro', 'Bairro*', ['class' => 'col-sm-3 control-label']) !!}

                                            <div class="col-sm-9">
                                                @haspermission('seguranca.profile.edit')
                                                {!! Form::text('pes_bairro', old('pes_bairro'), ['class' => 'form-control']) !!}
                                                @else
                                                    {!! Form::text('pes_bairro', old('pes_bairro'), ['disabled','class' => 'form-control']) !!}
                                                @endif
                                                @if ($errors->has('pes_bairro')) <p
                                                        class="help-block">{{ $errors->first('pes_bairro') }}</p> @endif
                                            </div>
                                        </div>
                                        <div class="form-group @if ($errors->has('pes_cidade')) has-error @endif">
                                            {!! Form::label('pes_cidade', 'Cidade*', ['class' => 'col-sm-3 control-label']) !!}

                                            <div class="col-sm-9">
                                                @haspermission('seguranca.profile.edit')
                                                {!! Form::text('pes_cidade', old('pes_cidade'), ['class' => 'form-control']) !!}
                                                @else
                                                    {!! Form::text('pes_cidade', old('pes_cidade'), ['disabled','class' => 'form-control']) !!}
                                                @endif
                                                @if ($errors->has('pes_cidade')) <p
                                                        class="help-block">{{ $errors->first('pes_cidade') }}</p> @endif
                                            </div>
                                        </div>
                                        <div class="form-group @if ($errors->has('pes_estado')) has-error @endif">
                                            {!! Form::label('pes_estado', 'Estado*', ['class' => 'col-sm-3 control-label']) !!}
                                            <div class="col-sm-9">
                                                @haspermission('seguranca.profile.edit')
                                                {!! Form::select('pes_estado',[
                                                    "AC" => "Acre",
                                                    "AL" => "Alagoas",
                                                    "AP" => "Amapá",
                                                    "AM" => "Amazonas",
                                                    "BA" => "Bahia",
                                                    "CE" => "Ceará",
                                                    "DF" => "Distrito Federal",
                                                    "ES" => "Espirito Santo",
                                                    "GO" => "Goiás",
                                                    "MA" => "Maranhão",
                                                    "MT" => "Mato Grosso",
                                                    "MS" => "Mato Grosso do Sul",
                                                    "MG" => "Minas Gerais",
                                                    "PA" => "Pará",
                                                    "PB" => "Paraiba",
                                                    "PR" => "Paraná",
                                                    "PE" => "Pernambuco",
                                                    "PI" => "Piauí",
                                                    "RJ" => "Rio de Janeiro",
                                                    "RN" => "Rio Grande do Norte",
                                                    "RS" => "Rio Grande do Sul",
                                                    "RO" => "Rondônia",
                                                    "RR" => "Roraima",
                                                    "SC" => "Santa Catarina",
                                                    "SP" => "São Paulo",
                                                    "SE" => "Sergipe",
                                                    "TO" => "Tocantis"
                                                ], old('pes_estado'), ['placeholder' => 'Selecione um estado', 'class' => 'form-control', 'style' => 'width: 100%;']) !!}
                                                @else
                                                    {!! Form::select('pes_estado',[
                                                        "AC" => "Acre",
                                                        "AL" => "Alagoas",
                                                        "AP" => "Amapá",
                                                        "AM" => "Amazonas",
                                                        "BA" => "Bahia",
                                                        "CE" => "Ceará",
                                                        "DF" => "Distrito Federal",
                                                        "ES" => "Espirito Santo",
                                                        "GO" => "Goiás",
                                                        "MA" => "Maranhão",
                                                        "MT" => "Mato Grosso",
                                                        "MS" => "Mato Grosso do Sul",
                                                        "MG" => "Minas Gerais",
                                                        "PA" => "Pará",
                                                        "PB" => "Paraiba",
                                                        "PR" => "Paraná",
                                                        "PE" => "Pernambuco",
                                                        "PI" => "Piauí",
                                                        "RJ" => "Rio de Janeiro",
                                                        "RN" => "Rio Grande do Norte",
                                                        "RS" => "Rio Grande do Sul",
                                                        "RO" => "Rondônia",
                                                        "RR" => "Roraima",
                                                        "SC" => "Santa Catarina",
                                                        "SP" => "São Paulo",
                                                        "SE" => "Sergipe",
                                                        "TO" => "Tocantis"
                                                    ], old('pes_estado'), ['disabled','placeholder' => 'Selecione um estado', 'class' => 'form-control', 'style' => 'width: 100%;']) !!}
                                                @endif

                                                @if ($errors->has('pes_estado')) <p
                                                        class="help-block">{{ $errors->first('pes_estado') }}</p> @endif
                                            </div>
                                        </div>

                                        @haspermission('seguranca.profile.edit')
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-primary">Atualizar Endereço</button>
                                            </div>
                                        </div>
                                        @endhaspermission


                                        @haspermission('seguranca.profile.edit')

                                        @else

                                        @endif


                                        {!! Form::close() !!}
                                    </div>
                                    <!-- /.tab-pane -->

                                    <div class="tab-pane @if ($errors->has('usr_senha') or $errors->has('usr_senha_nova') or $errors->has('usr_senha_nova_confirmation')) active @endif" id="senha">
                                        {!! Form::model($usuario->pessoa,["route" => "seguranca.profile.updatepassword", "method" => "PUT", "id" => "form", "role" => "form", "class" => "form-horizontal"]) !!}
                                        <div class="form-group @if ($errors->has('usr_senha')) has-error @endif">
                                            {!! Form::label('usr_senha', 'Senha atual*', ['class' => 'col-sm-3 control-label']) !!}

                                            <div class="col-sm-9">
                                                {!! Form::password('usr_senha', ['class' => 'form-control']) !!}
                                                @if ($errors->has('usr_senha')) <p
                                                        class="help-block">{{ $errors->first('usr_senha') }}</p> @endif
                                            </div>
                                        </div>
                                        <div class="form-group @if ($errors->has('usr_senha_nova')) has-error @endif">
                                            {!! Form::label('usr_senha_nova', 'Nova senha*', ['class' => 'col-sm-3 control-label']) !!}

                                            <div class="col-sm-9">
                                                {!! Form::password('usr_senha_nova', ['class' => 'form-control']) !!}
                                                @if ($errors->has('usr_senha_nova')) <p
                                                        class="help-block">{{ $errors->first('usr_senha_nova') }}</p> @endif
                                            </div>
                                        </div>
                                        <div class="form-group @if ($errors->has('usr_senha_nova_confirmation')) has-error @endif">
                                            {!! Form::label('usr_senha_nova_confirmation', 'Repita a nova senha*', ['class' => 'col-sm-3 control-label']) !!}

                                            <div class="col-sm-9">
                                                {!! Form::password('usr_senha_nova_confirmation', ['class' => 'form-control']) !!}
                                                @if ($errors->has('usr_senha_nova_confirmation')) <p
                                                        class="help-block">{{ $errors->first('usr_senha_nova_confirmation') }}</p> @endif
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-danger">Alterar senha</button>
                                            </div>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>

                                    <div class="tab-pane" id="foto">
                                        {!! Form::model($usuario->pessoa,["route" => "seguranca.profile.picture", "method" => "PUT", "id" => "form", "role" => "form", "class" => "form-horizontal", "enctype" => "multipart/form-data"]) !!}

                                        <div class="form-group @if ($errors->has('usr_picture')) has-error @endif">
                                            <div class="col-sm-9">
                                                {!! Form::file('usr_picture', ['class' => 'form-control file']) !!}
                                                @if ($errors->has('usr_picture')) <p class="help-block">{{ $errors->first('usr_picture') }}</p> @endif
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                                <button type="submit" class="btn btn-danger">Alterar foto</button>
                                            </div>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                    <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->
                            </div><!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
    </div>
@stop