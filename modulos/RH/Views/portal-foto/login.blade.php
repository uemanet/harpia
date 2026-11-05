@extends('layouts.site')

@section('content')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('img/logo.png') }}" style="height:70px" />
            </a>
            <h5 class="text-center" style="margin-top:2px">Sistema de Gestão <b>Educacional</b></h5>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="text-center mb-4">Envio de Foto Facial</h4>

                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                <form action="{{ route('portal.foto.authenticate') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>CPF</label>
                        <input type="text" name="cpf" class="form-control cpf-mask" required>
                    </div>
                    <div class="mb-3">
                        <label>Data de Nascimento</label>
                        <input type="text" name="data_nascimento" class="form-control datepicker" data-provide="datepicker" date-date-format="dd/mm/yyyy" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Validar Acesso</button>
                </form>
            </div>
        </div>
    </div>
@stop
