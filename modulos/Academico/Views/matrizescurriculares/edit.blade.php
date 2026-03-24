@extends('layouts.modulos.default')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">

@endsection

@section('title')
    Matrizes Curriculares
@stop

@section('subtitle')
    Alterar matriz curricular :: {{$matrizCurricular->mtc_id}}
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de edição de departamento</h3>
        </div>
        <div class="card-body">
            <form action="url(" method="POST" id="form" role="form" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    {{-- Form model: $matrizCurricular - inputs devem usar old('campo', $matrizCurricular->campo) --}}
                @include('Academico::matrizescurriculares.includes.formulario_edit')
            </form>
        </div>
    </div>
@stop