@extends('layouts.modulos.academico')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
    <link rel="stylesheet" href="{{asset('/css/plugins/datepicker3.css')}}">
@endsection

@section('title')
    Matrizes Curriculares
@stop

@section('subtitle')
    Alterar matriz curricular :: {{$matrizCurricular->mtc_id}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de departamento</h3>
        </div>
        <div class="box-body">
            {!! Form::model($matrizCurricular,["url" => url('/') . "/academico/matrizescurriculares/edit/$matrizCurricular->mtc_id", "method" => "PUT", "id" => "form", "role" => "form", "enctype" => "multipart/form-data"]) !!}
                @include('Academico::matrizescurriculares.includes.formulario_edit')
            {!! Form::close() !!}
        </div>
    </div>
@stop