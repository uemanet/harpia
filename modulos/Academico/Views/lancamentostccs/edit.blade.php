@extends('layouts.modulos.seguranca')

@section('stylesheets')
  <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
  <link rel="stylesheet" href="{{asset('/css/plugins/datepicker3.css')}}">
@endsection

@section('title')
    Lançamento de Tccs
@stop

@section('subtitle')
    Atualização de lançamento de TCC
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><b>Aluno</b>: {{$lancamentoTcc->matriculaOferta->matriculaCurso->aluno->pessoa->pes_nome}} <b>Disciplina</b>: {{$disciplina->dis_nome}}</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('academico.lancamentostccs.edit', [$lancamentoTcc->ltc_id]) }}" method="POST" id="form" role="form" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    {{-- Form model: $lancamentoTcc - inputs devem usar old('campo', $lancamentoTcc->campo) --}}
                 @include('Academico::lancamentostccs.includes.formulario_edit')
            </form>
        </div>
    </div>
@stop
