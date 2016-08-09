@extends('layouts.site')

@section('content')
    <div class="login-box" style="padding-top:10vh">
        <div class="box box-widget widget-user" style="margin-bottom:5px">
            <div class="widget-user-header text-center" style="background-color:#E9F1F5;border-bottom:2px solid #0083CE">
                <img src="{{url('/')}}/img/logo.png" style="height:70px" />
                <h4 class="text-center" style="margin-top:2px">Sistema de Gestão <b>Educacional</b></h4>
            </div>
            <div class="box-content">
                <div class="login-box-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops! </strong>Usuário e/ou senha incorreto(s).
                        </div>
                    @endif
                    <p class="login-box-msg">Preencha os dados abaixo para acessar</p>
                    <form action="{{url('/login')}}" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group has-feedback @if ($errors->has('usr_usuario')) has-error @endif">
                            {!! Form::text('usr_usuario', old('usr_usuario'), array('placeholder' => 'Usuario', 'class'=>'form-control')) !!}
                            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                            @if ($errors->has('usr_usuario')) <p class="help-block">{{ $errors->first('usr_usuario') }}</p> @endif
                        </div>
                        <div class="form-group has-feedback @if ($errors->has('usr_senha')) has-error @endif">
                            {!! Form::password('usr_senha', array('placeholder' => 'Senha', 'class'=>'form-control')) !!}
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                            @if ($errors->has('usr_senha')) <p class="help-block">{{ $errors->first('usr_senha') }}</p> @endif
                        </div>

                        <div class="row">
                            <div class="col-xs-8">
                                <div class="checkbox icheck">
                                    <label>
                                        <input type="checkbox" name="remember"> Lembrar-me
                                    </label>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <button type="submit" class="btn btn-primary btn-block btn-flat mt-lg">Acessar</button>
                            </div><!-- /.col -->
                        </div>
                    </form>
                </div>
                <div class="box-footer" style="padding-top:5px">
                    <a class="text-right col-md-12" href="#">Esqueceu sua senha?</a>
                </div>
            </div>
        </div>
        <footer class="main-footer" style="margin-left:0px;padding:5px;text-align:center;">
            <strong style="font-size:12px">Copyright © 2016-2016 <a href="http://www.uemanet.uema.br">UemaNet</a>.</strong> All rights
            reserved.
        </footer>
    </div>
@stop