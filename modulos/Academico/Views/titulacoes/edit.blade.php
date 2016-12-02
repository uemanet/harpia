@extends('layouts.modulos.academico')

@section('title')
    Titulações
@stop

@section('subtitle')
    Edição de titulações
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de titulações</h3>
        </div>
        <div class="box-body">
            {!! Form::model($titulacao, ["url" => url('/') . "/academico/titulacoes/edit/$titulacao->tit_id", "method" => "PUT", "id" => "form", "role" => "form"]) !!}
                @include('Academico::titulacoes.includes.formulario')
            {!! Form::close() !!}

        </div>
    </div>
@stop
