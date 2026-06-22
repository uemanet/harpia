@extends('layouts.modulos.default')

@section('title')
    Lançamento de TCC
@stop

@section('subtitle')
    Cadastro de TCC
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline p-0">
            <div class="card-header with-border">
                <h3 class="card-title"><b>Aluno</b>: {{$aluno->pessoa->pes_nome}} <b>Disciplina</b>: {{$disciplina->dis_nome}}</h3>
            </div>
            <form action="{{ route('academico.lancamentostccs.create') }}" method="POST" id="form" role="form" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    @include('Academico::lancamentostccs.includes.formulario')
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
                </div>
            </form>
        </div>
    </div>
@stop
