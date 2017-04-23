@extends('layouts.modulos.geral')

@section('title')
    Pessoas
@stop

@section('subtitle')
    Cadastro de pessoas
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de pessoas</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["route" => 'geral.pessoas.create', "method" => "POST", "id" => "form", "role" => "form"]) !!}
                @include('Geral::pessoas.includes.formulario')

                <div class="row">
                    <div class="form-group col-md-12">
                        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop