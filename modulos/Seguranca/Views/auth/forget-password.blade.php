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
                {{-- TODO: FIX ALERTS--}}
                @if (isset($sent))
                    <div class="alert alert-success">
                        Nós lhe enviamos por email um link de redefinição de senha!
                    </div>
                @endif

                <p class="login-box-msg"><b>Redefinição de Senha</b></p>

                <form action="{{url('/forget-password')}}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        {!! Form::text('email', old('email'), array('placeholder' => 'E-mail', 'class'=>'form-control')) !!}
                        <div class="input-group-text">
                            <span class="bi bi-envelope"></span>
                        </div>
                        @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
                    </div>
                    <!--begin::Row-->
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-12 text-center">
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