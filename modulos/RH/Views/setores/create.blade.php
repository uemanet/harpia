@extends('layouts.modulos.default')

@section('title')
    Setores
@stop

@section('subtitle')
    Cadastro de setores
@stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title m-0">Formulário de cadastro de setores</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('rh.setores.create') }}" method="POST" id="form" role="form">
    @csrf
            @include('RH::setores.includes.formulario')
            </form>
        </div>
    </div>
@stop
