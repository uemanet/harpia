@extends('layouts.modulos.academico')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/datepicker3.css')}}">
@endsection

@section('title')
    Períodos Letivos
@stop

@section('subtitle')
    Cadastro de período letivo
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de períodos letivos</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["url" => url('/') . "/academico/periodosletivos/create", "method" => "POST", "id" => "form", "role" => "form"]) !!}
              @include('Academico::periodosletivos.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{asset('/js/plugins/bootstrap-datepicker.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/plugins/bootstrap-datepicker.pt-BR.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        $('.datepicker').datepicker({
           format: 'dd/mm/yyyy'
        });
    </script>
@endsection