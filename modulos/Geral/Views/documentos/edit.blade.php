@extends('layouts.modulos.default')

@section('title')
    Documento
@stop

@section('subtitle')
    Alterar documento :: {{$documentotipo}}
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title m-0">Formulário de edição de documento</h3>
            </div>
            {!! Form::model($documento,["route" => ['geral.pessoas.documentos.edit',$documento->doc_id], "method" => "PUT", "id" => "form", "role" => "form", "enctype" => "multipart/form-data"]) !!}
            <div class="card-body">
                <div class="row">
                    @include('Geral::documentos.includes.formulario_edit')
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop
