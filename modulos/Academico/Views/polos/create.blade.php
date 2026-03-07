@extends('layouts.modulos.academico')

@section('title')
    Polos
@stop

@section('subtitle')
    Cadastro de polo
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de polos</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('academico.polos.create') }}" method="POST" id="form" role="form">
    @csrf
            @include('Academico::polos.includes.formulario')
            </form>
        </div>
    </div>
@stop
