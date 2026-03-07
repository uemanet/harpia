@extends('layouts.modulos.seguranca')

@section('title')
    Turmas
@stop

@section('subtitle')
    Alterar turma :: {{$turma->trm_nome}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de turma</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('academico.ofertascursos.turmas.edit', [$turma->trm_id]) }}" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $turma - inputs devem usar old('campo', $turma->campo) --}}
                 @include('Academico::turmas.includes.formulario_edit')
            </form>
        </div>
    </div>
@stop
