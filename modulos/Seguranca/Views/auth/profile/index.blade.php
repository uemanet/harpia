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
    <div class="container" style="padding-top: 20px;">
        <div class="row">
            <div class="col-md-3">

                <!-- Profile Image -->
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-circle" src="{{{ route('seguranca.profile.profile-picture', \Illuminate\Support\Facades\Auth::user()->usr_profile_picture_id ?? 0) }}}"
                             alt="User profile picture">

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
                        <li class="@if (!$errors->has('usr_senha') and !$errors->has('usr_senha_nova') and !$errors->has('usr_senha_nova_confirmation')) active @endif"><a href="#dados" data-toggle="tab">Dados pessoais</a></li>
                        <li><a href="#endereco" data-toggle="tab">Endereço</a></li>
                        <li class = "@if ($errors->has('usr_senha') or $errors->has('usr_senha_nova') or $errors->has('usr_senha_nova_confirmation')) active @endif"><a href="#senha" data-toggle="tab">Alterar Senha</a></li>
                        <li><a href="#foto" data-toggle="tab">Alterar Foto</a></li>
                    </ul>
                    <div class="tab-content">

                        <div class="tab-pane @if (!$errors->has('usr_senha') and !$errors->has('usr_senha_nova') and !$errors->has('usr_senha_nova_confirmation')) active @endif" id="dados">
                            <form action="{{ route('seguranca.profile.edit') }}" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $usuario->pessoa - inputs devem usar old('campo', $usuario->pessoa->campo) --}}
                            <div class="form-group @if ($errors->has('pes_nome')) has-error @endif">
                                <label for="pes_nome" class="col-sm-3 control-label">Nome completo*</label>

                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <input type="text" name="pes_nome" value="{{ old('pes_nome') }}" class="form-control" >
                                    @else
                                        <input type="text" name="pes_nome" value="{{ old('pes_nome') }}" class="form-control" >
                                    @endif
                                    @if ($errors->has('pes_nome')) <p
                                            class="help-block">{{ $errors->first('pes_nome') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_email')) has-error @endif">
                                <label for="pes_email" class="col-sm-3 control-label">Email*</label>
                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <input type="email" name="pes_email" value="{{ old('pes_email') }}" class="form-control" >
                                    @else
                                        <input type="email" name="pes_email" value="{{ old('pes_email') }}" class="form-control" >
                                    @endif
                                    @if ($errors->has('pes_email')) <p
                                            class="help-block">{{ $errors->first('pes_email') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_telefone')) has-error @endif">
                                <label for="pes_telefone" class="col-sm-3 control-label">Telefone*</label>

                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <input type="text" name="pes_telefone" value="{{ old('pes_telefone') }}" class="form-control" >
                                    @else
                                        <input type="text" name="pes_telefone" value="{{ old('pes_telefone') }}" class="form-control" >
                                    @endif
                                    @if ($errors->has('pes_telefone')) <p
                                            class="help-block">{{ $errors->first('pes_telefone') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_sexo')) has-error @endif">
                                <label for="pes_sexo" class="col-sm-3 control-label">Sexo*</label>

                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <select name="pes_sexo" class="form-control">
    <option value="M" {{ old('pes_sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
    <option value="F" {{ old('pes_sexo') == 'F' ? 'selected' : '' }}>Feminino</option>
</select>
                                    @else
                                        <select name="pes_sexo" class="form-control">
    <option value="M" {{ old('pes_sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
    <option value="F" {{ old('pes_sexo') == 'F' ? 'selected' : '' }}>Feminino</option>
</select>
                                    @endif
                                    @if ($errors->has('pes_sexo')) <p
                                            class="help-block">{{ $errors->first('pes_sexo') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_nascimento')) has-error @endif">
                                <label for="pes_nascimento" class="col-sm-3 control-label">Nascimento*</label>

                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <input type="text" name="pes_nascimento" value="{{ old('pes_nascimento') }}" class="form-control datepicker" >
                                    @else
                                        <input type="text" name="pes_nascimento" value="{{ old('pes_nascimento') }}" class="form-control datepicker" >
                                    @endif
                                    @if ($errors->has('pes_nascimento')) <p
                                            class="help-block">{{ $errors->first('pes_nascimento') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_estado_civil')) has-error @endif">
                                <label for="pes_estado_civil" class="col-sm-3 control-label">Estado Civil*</label>

                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <select name="pes_estado_civil" class="form-control">
    <option value="uniao_estavel" {{ old('pes_estado_civil') == 'uniao_estavel' ? 'selected' : '' }}>União estável</option>
    <option value="outro" {{ old('pes_estado_civil') == 'outro' ? 'selected' : '' }}>Outro</option>
</select>
                                    @else
                                        <select name="pes_estado_civil" class="form-control">
    <option value="uniao_estavel" {{ old('pes_estado_civil') == 'uniao_estavel' ? 'selected' : '' }}>União estável</option>
    <option value="outro" {{ old('pes_estado_civil') == 'outro' ? 'selected' : '' }}>Outro</option>
</select>
                                    @endif
                                    @if ($errors->has('pes_estado_civil')) <p
                                            class="help-block">{{ $errors->first('pes_estado_civil') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_mae')) has-error @endif">
                                <label for="pes_mae" class="col-sm-3 control-label">Nome da mãe*</label>

                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <input type="text" name="pes_mae" value="{{ old('pes_mae') }}" class="form-control" >
                                    @else
                                        <input type="text" name="pes_mae" value="{{ old('pes_mae') }}" class="form-control" >
                                    @endif
                                    @if ($errors->has('pes_mae')) <p
                                            class="help-block">{{ $errors->first('pes_mae') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_pai')) has-error @endif">
                                <label for="pes_pai" class="col-sm-3 control-label">Nome do pai</label>

                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <input type="text" name="pes_pai" value="{{ old('pes_pai') }}" class="form-control" >
                                    @else
                                        <input type="text" name="pes_pai" value="{{ old('pes_pai') }}" class="form-control" >
                                    @endif
                                    @if ($errors->has('pes_pai')) <p
                                            class="help-block">{{ $errors->first('pes_pai') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_naturalidade')) has-error @endif">
                                <label for="pes_naturalidade" class="col-sm-3 control-label">Naturalidade*</label>

                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <input type="text" name="pes_naturalidade" value="{{ old('pes_naturalidade') }}" class="form-control" >
                                    @else
                                        <input type="text" name="pes_naturalidade" value="{{ old('pes_naturalidade') }}" class="form-control" >
                                    @endif
                                    @if ($errors->has('pes_naturalidade')) <p
                                            class="help-block">{{ $errors->first('pes_naturalidade') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_nacionalidade')) has-error @endif">
                                <label for="pes_nacionalidade" class="col-sm-3 control-label">Nacionalidade*</label>

                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <input type="text" name="pes_nacionalidade" value="{{ old('pes_nacionalidade') }}" class="form-control" >
                                    @else
                                        <input type="text" name="pes_nacionalidade" value="{{ old('pes_nacionalidade') }}" class="form-control" >
                                    @endif
                                    @if ($errors->has('pes_nacionalidade')) <p
                                            class="help-block">{{ $errors->first('pes_nacionalidade') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_raca')) has-error @endif">
                                <label for="pes_raca" class="col-sm-3 control-label">Cor/Raça*</label>

                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <select name="pes_raca" class="form-control">
    <option value="branca" {{ old('pes_raca') == 'branca' ? 'selected' : '' }}>Branca</option>
    <option value="preta" {{ old('pes_raca') == 'preta' ? 'selected' : '' }}>Preta</option>
    <option value="parda" {{ old('pes_raca') == 'parda' ? 'selected' : '' }}>Parda</option>
    <option value="amarela" {{ old('pes_raca') == 'amarela' ? 'selected' : '' }}>Amarela</option>
    <option value="indigena" {{ old('pes_raca') == 'indigena' ? 'selected' : '' }}>Indígena</option>
    <option value="outra" {{ old('pes_raca') == 'outra' ? 'selected' : '' }}>Outra</option>
</select>
                                    @else
                                        <select name="pes_raca" class="form-control">
    <option value="branca" {{ old('pes_raca') == 'branca' ? 'selected' : '' }}>Branca</option>
    <option value="preta" {{ old('pes_raca') == 'preta' ? 'selected' : '' }}>Preta</option>
    <option value="parda" {{ old('pes_raca') == 'parda' ? 'selected' : '' }}>Parda</option>
    <option value="amarela" {{ old('pes_raca') == 'amarela' ? 'selected' : '' }}>Amarela</option>
    <option value="indigena" {{ old('pes_raca') == 'indigena' ? 'selected' : '' }}>Indígena</option>
    <option value="outra" {{ old('pes_raca') == 'outra' ? 'selected' : '' }}>Outra</option>
</select>
                                    @endif
                                    @if ($errors->has('pes_raca')) <p
                                            class="help-block">{{ $errors->first('pes_raca') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_necessidade_especial')) has-error @endif">
                                <label for="pes_necessidade_especial" class="col-sm-3 control-label">Necessidade especial?*</label>

                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <select name="pes_necessidade_especial" class="form-control">
    <option value="S" {{ old('pes_necessidade_especial') == 'S' ? 'selected' : '' }}>Sim</option>
    <option value="N" {{ old('pes_necessidade_especial') == 'N' ? 'selected' : '' }}>Não</option>
</select>
                                    @else
                                        <select name="pes_necessidade_especial" class="form-control">
    <option value="S" {{ old('pes_necessidade_especial') == 'S' ? 'selected' : '' }}>Sim</option>
    <option value="N" {{ old('pes_necessidade_especial') == 'N' ? 'selected' : '' }}>Não</option>
</select>
                                    @endif
                                    @if ($errors->has('pes_necessidade_especial')) <p
                                            class="help-block">{{ $errors->first('pes_necessidade_especial') }}</p> @endif
                                </div>
                            </div>

                            <div class="form-group @if ($errors->has('pes_estrangeiro')) has-error @endif">
                                <label for="pes_estrangeiro" class="col-sm-3 control-label">Estrangeiro?*</label>

                                <div class="col-sm-9">
                                    @haspermission('seguranca.profile.edit')
                                        <select name="pes_estrangeiro" class="form-control">
    <option value="0" {{ old('pes_estrangeiro') == '0' ? 'selected' : '' }}>Não</option>
    <option value="1" {{ old('pes_estrangeiro') == '1' ? 'selected' : '' }}>Sim</option>
</select>
                                    @else
                                        <select name="pes_estrangeiro" class="form-control">
    <option value="0" {{ old('pes_estrangeiro') == '0' ? 'selected' : '' }}>Não</option>
    <option value="1" {{ old('pes_estrangeiro') == '1' ? 'selected' : '' }}>Sim</option>
</select>
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
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
@stop