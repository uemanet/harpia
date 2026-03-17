@extends('layouts.site')

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/logo.png') }}" style="height:70px" />
            </a>
            <h5 class="text-center" style="margin-top:2px">Sistema de Gestão <b>Educacional</b></h5>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Preencha os dados abaixo para acessar</p>

                {{-- TODO: FIX ALERTS--}}
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops! </strong>Usuário e/ou senha incorreto(s).
                    </div>
                @endif

                <form action="{{ url('/login') }}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        {!! Form::text('usr_usuario', old('usr_usuario'), array('placeholder' => 'Usuario', 'class'=>'form-control')) !!}
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>
                        @if ($errors->has('usr_usuario')) <small class="help-block">{{ $errors->first('usr_usuario') }}</small> @endif
                    </div>
                    <div class="input-group mb-3">
                        {!! Form::password('usr_senha', array('placeholder' => 'Senha', 'class'=>'form-control')) !!}
                        <div class="input-group-text">
                            <span class="bi bi-lock-fill"></span>
                        </div>
                        @if ($errors->has('usr_senha')) <p class="help-block">{{ $errors->first('usr_senha') }}</p> @endif
                    </div>
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-8">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" name="remember" />
                                <label class="form-check-label" for="remember"> Lembrar-me </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Acessar</button>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!--end::Row-->
                </form>

                <p class="mb-1">
                    <a class="text-right col-md-12" href="{{url('/forget-password')}}">Esqueceu sua senha?</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
@stop