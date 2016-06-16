@extends('layouts.clean')

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
                    <img class="profile-user-img img-responsive img-circle" src="{{url('/')}}/img/user.jpg" alt="User profile picture">

                    <h3 class="profile-username text-center">{{$pessoa->pes_nome}}</h3>

                    <p class="text-muted text-center">{{$pessoa->pes_email}}</p>
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
                    <li><a href="#senha" data-toggle="tab">Alterar Senha</a></li>
                </ul>
                <div class="tab-content">
                    <div class="active tab-pane" id="dados">
                        {!! Form::model($pessoa,["url" => url('/') . "/seguranca/profile/edit/$pessoa->pes_id", "method" => "PUT", "id" => "form", "role" => "form", "class" => "form-horizontal"]) !!}
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
                                    {!! Form::date('pes_nascimento', old('pes_nascimento'), ['class' => 'form-control']) !!}
                                    @if ($errors->has('pes_nascimento')) <p class="help-block">{{ $errors->first('pes_nascimento') }}</p> @endif
                                </div>
                            </div>
                            <div class="form-group @if ($errors->has('pes_estado_civil')) has-error @endif">
                                {!! Form::label('pes_estado_civil', 'Sexo*', ['class' => 'col-sm-3 control-label']) !!}

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

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary">Atualizar informações</button>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                    <div class="tab-pane" id="senha">
                        {!! Form::model($pessoa,["url" => url('/') . "/seguranca/profile/updatepassword", "method" => "POST", "id" => "form", "role" => "form", "class" => "form-horizontal"]) !!}
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