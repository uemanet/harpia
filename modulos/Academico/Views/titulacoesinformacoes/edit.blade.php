@extends('layouts.modulos.seguranca')

@section('title')
    Titulacoes
@stop

@section('subtitle')
    Alterar titulação :: {{$titulacaoInfo->tin_titulo}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de Edição de Titulação</h3>
        </div>
        <div class="box-body">
            {!! Form::model($titulacaoInfo,["url" => url('/') . "/academico/titulacoesinformacoes/edit/$titulacaoInfo->tin_id", "method" => "PUT", "id" => "form", "role" => "form"]) !!}
            {{ Form::hidden('tin_pes_id', $pessoa) }}
            @include('Academico::titulacoesinformacoes.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop