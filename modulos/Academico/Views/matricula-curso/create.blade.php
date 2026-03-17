@extends('layouts.modulos.default')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection

@section('title')
    Nova Matricula
@stop

@section('subtitle')
    Aluno: {{$aluno->pessoa->pes_nome}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de Matrícula</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('academico.matricularalunocurso.create', [$aluno->alu_id]) }}" method="POST" id="form" role="form">
    @csrf
                @include('Academico::matricula-curso.includes.formulario')
            </form>
        </div>
    </div>
@stop