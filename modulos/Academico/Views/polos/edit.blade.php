@extends('layouts.modulos.academico')

@section('title')
    Polos
@stop

@section('subtitle')
    Edição de polo
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de polos</h3>
        </div>
        <div class="box-body">
            {!! Form::model($polo, ["url" => url('/') . "/academico/polos/edit/$polo->pol_id", "method" => "PUT", "id" => "form", "role" => "form"]) !!}
                @include('Academico::polos.includes.formulario')
            {!! Form::close() !!}

        </div>
    </div>
@stop
