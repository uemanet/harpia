@extends('layouts.modulos.academico')

@section('title')
    Ofertas de Turmas
@stop

@section('subtitle')
    Cadastro de oferta de turmas
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de turmas</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('academico.ofertascursos.turmas.create') }}" method="POST" id="form" role="form">
    @csrf
                @include('Academico::turmas.includes.formulario_create')
            </form>
        </div>
    </div>
@stop
