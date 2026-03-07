@extends('layouts.modulos.rh')

@section('title')
    Bancos
@stop

@section('subtitle')
    Cadastro de bancos
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de banco</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('rh.bancos.create') }}" method="POST" id="form" role="form">
    @csrf
            @include('RH::bancos.includes.formulario')
            </form>
        </div>
    </div>
@stop
