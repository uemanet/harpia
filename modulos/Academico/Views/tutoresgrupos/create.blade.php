@extends('layouts.modulos.default')

@section('title')
    Tutores do grupo
@stop

@section('subtitle')
    Vínculo de tutores
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline p-0">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de vínculo de tutores</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('academico.ofertascursos.turmas.grupos.tutoresgrupos.create') }}" method="POST" id="form" role="form">
    @csrf
                @include('Academico::tutoresgrupos.includes.formulario')
            </form>
        </div>
    </div>
@stop
