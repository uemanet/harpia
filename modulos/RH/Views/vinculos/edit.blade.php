@extends('layouts.modulos.rh')

@section('title')
    Vínculos
@stop

@section('subtitle')
    Edição de vínculo
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de vínculo</h3>
        </div>
        <div class="box-body">
            {!! Form::model($vinculo, ["route" => ['rh.vinculos.edit',$vinculo->vin_id], "method" => "PUT", "id" => "form", "role" => "form"]) !!}
                @include('RH::vinculos.includes.formulario')
            {!! Form::close() !!}

        </div>
    </div>
@stop
