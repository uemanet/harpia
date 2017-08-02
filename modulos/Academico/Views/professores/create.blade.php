@extends('layouts.modulos.academico')

@section('title')
    Professores
@stop

@section('subtitle')
    Cadastro de professores
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de Cadastro de Professores</h3>
            <span class="label label-primary pull-right">Professor</span>
        </div>
        <div class="box-body">
            {!! Form::open(["route" => 'academico.professores.create', "method" => "POST", "id" => "form", "role" => "form"]) !!}

            <h4 class="box-title">
                Dados de Pessoa
            </h4>
            @include('Academico::professores.includes.formulario', ['pessoa' => $pessoa])

            <div class="row">
                <div class="form-group col-md-12">
                    {!! Form::submit('Salvar Professor', ['class' => 'btn btn-primary pull-right']) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop
