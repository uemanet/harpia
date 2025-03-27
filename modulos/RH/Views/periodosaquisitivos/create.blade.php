@extends('layouts.modulos.rh')

@section('breadcrumbs')
    {{ Breadcrumbs::render('rh.colaboradores.periodosgozo.create', $colaborador->col_id) }}
@endsection

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
    <link rel="stylesheet" href="{{asset('/css/plugins/datepicker3.css')}}">
@endsection

@section('title')
    Férias
@stop

@section('subtitle')
    Cadastro de Férias
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de Férias</h3>
        </div>
        <div class="box-body">
            {!! Form::open(['route' => ['rh.colaboradores.periodosgozo.create',  $colaborador->col_id], "method" => "POST", "id" => "form", "role" => "form"]) !!}
            @include('RH::periodosgozo.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop


@section('scripts')
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/plugins/bootstrap-datepicker.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/plugins/bootstrap-datepicker.pt-BR.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("select").select2();
        });
    </script>

    <script type="text/javascript">
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR'
        });
    </script>
@endsection
