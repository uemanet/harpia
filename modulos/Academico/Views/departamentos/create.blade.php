@extends('layouts.modulos.academico')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection

@section('title')
    Departamentos
@stop

@section('subtitle')
    Cadastro de departamento
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de departamentos</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["route" => 'academico.departamentos.create', "method" => "POST", "id" => "form", "role" => "form"]) !!}
                @include('Academico::departamentos.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("select").select2();
        });
    </script>
@endsection
