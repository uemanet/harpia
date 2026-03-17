@extends('layouts.modulos.default')

@section('title')
    Vínculos
@stop

@section('subtitle')
    Cadastro de vínculos
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de vínculos</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('rh.vinculos.create') }}" method="POST" id="form" role="form">
    @csrf
            @include('RH::vinculos.includes.formulario')
            </form>
        </div>
    </div>
@stop
