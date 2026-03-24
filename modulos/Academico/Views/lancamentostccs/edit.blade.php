@extends('layouts.modulos.default')

@section('title')
    Lançamento de TCCs
@stop

@section('subtitle')
    Atualização de lançamento de TCC
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
            <div class="card-header with-border">
                <h3 class="card-title"><b>Aluno</b>: {{$lancamentoTcc->matriculaOferta->matriculaCurso->aluno->pessoa->pes_nome}} <b>Disciplina</b>: {{$disciplina->dis_nome}}</h3>
            </div>
            <form action="{{ route('academico.lancamentostccs.edit', [$lancamentoTcc->ltc_id]) }}" method="POST" id="form" role="form" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                     @include('Academico::lancamentostccs.includes.formulario_edit')
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
                </div>
            </form>
        </div>
    </div>
@stop
