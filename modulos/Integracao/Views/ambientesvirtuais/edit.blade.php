@extends('layouts.modulos.integracao')

@section('title')
    Ambientes Virtuais
@stop

@section('subtitle')
    Edição de ambiente virtual
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de ambientes virtuais</h3>
        </div>
        <div class="box-body">
            {!! Form::model($ambientevirtual, ["url" => url('/') . "/integracao/ambientesvirtuais/edit/$ambientevirtual->amb_id", "method" => "PUT", "id" => "form", "role" => "form"]) !!}
                @include('Integracao::ambientesvirtuais.includes.formulario')
            {!! Form::close() !!}

        </div>
    </div>
@stop
