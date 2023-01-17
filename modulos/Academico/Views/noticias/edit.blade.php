@extends('layouts.modulos.academico')

@section('title')
    Notícias
@stop

@section('subtitle')
    Edição de notícias
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de notícias</h3>
        </div>
        <div class="box-body">
            {!! Form::model($noticia, ["route" => ['academico.noticias.edit',$noticia->not_id], "method" => "PUT", "id" => "form", "role" => "form"]) !!}
                @include('Academico::noticias.includes.formulario')
            {!! Form::close() !!}

        </div>
    </div>
@stop
