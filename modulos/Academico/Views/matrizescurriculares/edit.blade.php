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
        <div class="card card-primary card-outline p-0">
            <div class="card-header with-border">
                <h3 class="card-title">Formulário de edição de departamento</h3>
            </div>
            <form action="{{ url('/') . "/academico/matrizescurriculares/edit/" . $matrizCurricular->mtc_id }}" method="POST" id="form" role="form" enctype="multipart/form-data">
                <div class="card-body">
                    @csrf
                    @method('PUT')
                    @include('Academico::matrizescurriculares.includes.formulario_edit')
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
                </div>
            </form>
        </div>
    </div>
@stop