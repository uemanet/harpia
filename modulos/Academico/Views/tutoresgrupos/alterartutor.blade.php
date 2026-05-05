@extends('layouts.modulos.default')

@section('title')
    Alterar tutor
@stop

@section('subtitle')
    Tutor atual: {{$tutor->pessoa->pes_nome}}
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline p-0">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de alteração de tutor do grupo. </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('academico.ofertascursos.turmas.grupos.tutoresgrupos.alterartutor', [$tutorgrupo->ttg_id]) }}" method="POST" id="form" role="form">
                @csrf
                @method('PUT')
                @include('Academico::tutoresgrupos.includes.formulario_alterar')
            </form>
        </div>
    </div>
@stop
