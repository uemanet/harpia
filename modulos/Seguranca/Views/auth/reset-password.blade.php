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

                <form action="{{url('/reset-password')}}" method="post">
                    <input type="hidden" name="token" value="{{ request()->token }}">
                    @csrf
                    <div class="input-group mb-3">
                        {!! Form::text('email', old('email'), array('placeholder' => 'Confirme seu email', 'class'=>'form-control')) !!}
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>
                        @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
                    </div>
                    <div class="input-group mb-3">
                        {!! Form::password('password', array('placeholder' => 'Nova Senha', 'class'=>'form-control')) !!}
                        <div class="input-group-text">
                            <span class="bi bi-lock-fill"></span>
                        </div>
                        @if ($errors->has('password')) <p class="help-block">{{ $errors->first('password') }}</p> @endif
                    </div>
                    <div class="input-group mb-3">
                        {!! Form::password('password_confirmation', array('placeholder' => 'Confirme sua nova senha', 'class'=>'form-control')) !!}
                        <div class="input-group-text">
                            <span class="bi bi-lock-fill"></span>
                        </div>
                        @if ($errors->has('password_confirmation')) <p class="help-block">{{ $errors->first('password_confirmation') }}</p> @endif
                    </div>
                    <!--begin::Row-->
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-12">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Enviar</button>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!--end::Row-->
                </form>

                <p class="mb-1">
                    <a class="text-right col-md-12" href="{{url('/login')}}">Login</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
@stop