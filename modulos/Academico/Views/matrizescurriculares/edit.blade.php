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
                @include('Academico::matrizescurriculares.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/plugins/bootstrap-datepicker.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/plugins/bootstrap-datepicker.pt-BR.js')}}" type="text/javascript"></script>


    <script type="text/javascript">
        $(document).ready(function () {
            $(".select-control").select2();
        });

        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR'
        });
    </script>
@endsection