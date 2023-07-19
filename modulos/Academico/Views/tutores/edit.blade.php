@extends('layouts.modulos.academico')

@section('title')
    Mediadores
@stop

@section('subtitle')
    Alterar mediador :: {{$pessoa->pes_nome}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de Edição de Mediador</h3>
        </div>
        <div class="box-body">
            {!! Form::model($pessoa,['route' => ['academico.tutores.edit', $pessoa->pes_id], "method" => "PUT", "id" => "form", "role" => "form"]) !!}

            <h4 class="box-title">
                Dados de Pessoa
            </h4>
            @include('Geral::pessoas.includes.formulario', ['pessoa' => $pessoa])

            <div class="row">
                <div class="form-group col-md-12">
                    {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop