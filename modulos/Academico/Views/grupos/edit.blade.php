@extends('layouts.modulos.academico')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection

@section('title')
    Edição de Grupo
@stop

@section('subtitle')
    Alterar grupo :: {{$grupo->grp_nome}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de grupo</h3>
        </div>
        <div class="box-body">
            {!! Form::model($grupo,["url" => url('/') . "/academico/grupos/edit/$grupo->grp_id", "method" => "PUT", "id" => "form", "role" => "form"]) !!}
            @include('Academico::grupos.includes.formulario_edit')
            {!! Form::close() !!}
        </div>
    </div>
@stop
