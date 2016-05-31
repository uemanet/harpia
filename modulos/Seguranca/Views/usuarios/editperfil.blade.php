@extends('layouts.interno')

@section('title')
    <div class="row">
        <div class="col-md-8">
            <h2 style="margin:0px;font-size:30px" class="lead" >Alterar Senha</h2>
        </div>
    </div>
@stop

@section('content')
    <div class="panel panel-default ">
        <div class="panel-body">
            <div class="ibox float-e-margins wrapper wrapper-content">
                <div class="ibox-title">
                    <h5>Alteração de Senha</h5>
                </div>

                <div class="ibox-content">
                    {!! Form::model($usuario,["url" => "/security/usuarios/editpassword/$usuario->usr_id", "method" => "PUT", "id" => "form", "role" => "form"]) !!}
                    {!! Form::hidden('usr_id') !!}
                    <div class="row">
                         <div class="form-group col-md-4 col-sm-4 col-xs-6 @if ($errors->has('usr_email')) has-error @endif">
                            {!! Form::label('usr_email', 'Email*', ['class' => 'control-label']) !!}
                            <div class="input-group col-lg-12">
                                {!! Form::email('usr_email', old('usr_email'), ['class' => 'form-control']) !!}
                                @if ($errors->has('usr_email')) <p class="help-block">{{ $errors->first('usr_email') }}</p> @endif
                            </div>
                        </div>
                     <div class="form-group col-md-2 col-xs-6 @if ($errors->has('usr_telefone')) has-error @endif">
                        {!! Form::label('usr_telefone', 'Celular', ['class' => 'control-label']) !!}
                        <div class="input-group col-lg-122">
                            {!! Form::text('usr_telefone', old('usr_telefone'), ['class' => 'form-control', 'data-mask' => '99 99999-9999']) !!}
                            @if ($errors->has('usr_telefone')) <p class="help-block">{{ $errors->first('usr_telefone') }}</p> @endif
                        </div>
                    </div>
                    <div class="form-group col-md-2 col-sm-4 @if ($errors->has('usr_senhaAtual')) has-error @endif">
                        {!! Form::label('usr_senhaAtual', 'Senha Atual*', ['class' => 'control-label']) !!}
                        <div class="input-group col-lg-12">
                            {!! Form::password('usr_senhaAtual', ['class' => 'form-control']) !!}
                            @if ($errors->has('usr_senhaAtual')) <p class="help-block">{{ $errors->first('usr_senhaAtual') }}</p> @endif
                        </div>
                    </div>
                    <div class="form-group col-md-2 col-sm-4 @if ($errors->has('usr_senha')) has-error @endif">
                        {!! Form::label('usr_senha', 'Nova Senha*', ['class' => 'control-label']) !!}
                        <div class="input-group col-lg-12">
                            {!! Form::password('usr_senha', ['class' => 'form-control']) !!}
                            @if ($errors->has('usr_senha')) <p class="help-block">{{ $errors->first('usr_senha') }}</p> @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop