@extends('layouts.modulos.rh')

@section('title')
    Fontes Pagadoras
@stop

@section('subtitle')
    Edição de Fonte Pagadora
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de fonte pagadora</h3>
        </div>
        <div class="box-body">
            {!! Form::model($fontepagadora, ["route" => ['rh.fontespagadoras.edit',$fontepagadora->fpg_id], "method" => "PUT", "id" => "form", "role" => "form"]) !!}
                @include('RH::fontespagadoras.includes.formulario')
            {!! Form::close() !!}

        </div>
    </div>
@stop
