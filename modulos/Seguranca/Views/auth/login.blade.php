@extends('layouts.site')

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="#">Sistema <b>ADMIN</b></a>
        </div><!-- /.login-logo -->
        <div class="login-box-body">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops! </strong>Usuário e/ou senha incorreto(s).
                </div>
            @endif
            <p class="login-box-msg">Faça o login para acessar</p>
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
                        <button type="submit" class="btn btn-primary btn-block btn-flat mt-lg">Sign In</button>
                    </div><!-- /.col -->
                </div>
            </form>

            <br>
            <a href="#">I forgot my password</a>
        </div>
    </div>
@stop