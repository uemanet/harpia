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
            {!! Form::model($lancamentoTcc,["route" => ['academico.lancamentostccs.edit',$lancamentoTcc->ltc_id], "method" => "PUT", "id" => "form", "role" => "form", "enctype" => "multipart/form-data"]) !!}
                 @include('Academico::lancamentostccs.includes.formulario_edit')
            {!! Form::close() !!}
        </div>
    </div>
@stop
