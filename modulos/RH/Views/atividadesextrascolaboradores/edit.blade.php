@extends('layouts.modulos.seguranca')

@section('title')
    Atividades Extras
@stop

@section('subtitle')
    Alterar titulação :: {{$atividade_extra->atc_titulo}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de Edição de Atividade Extra</h3>
        </div>
        <div class="box-body">
            {!! Form::model($atividade_extra,["route" => ['rh.colaboradores.atividadesextrascolaboradores.edit',$atividade_extra->atc_id], "method" => "PUT", "id" => "form", "role" => "form"]) !!}
            {{ Form::hidden('atc_col_id', $atividade_extra->colaborador->col_id) }}
            @include('RH::atividadesextrascolaboradores.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop