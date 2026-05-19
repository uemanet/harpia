@extends('layouts.modulos.default')

@section('title')
    Vínculos
@stop

@section('subtitle')
    Cadastro de vínculos
@stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title m-0">Formulário de cadastro de vínculos</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('rh.vinculos.create') }}" method="POST" id="form" role="form">
    @csrf
            @include('RH::vinculos.includes.formulario')
            </form>
        </div>
    </div>
@stop
