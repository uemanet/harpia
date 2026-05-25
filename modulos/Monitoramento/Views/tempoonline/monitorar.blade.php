@extends('layouts.modulos.default')

@section('title')
    Tempo Online
@stop

@section('subtitle')
    {{$ambiente->amb_nome}}
@stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Visualização de dados do ambiente virtual</h3>
        </div>
        <div class="card-body">
            @include('Monitoramento::tempoonline.includes.formulario')
        </div>
        <div class="text-center margin" id="grafico"></div>
    </div>
@stop
