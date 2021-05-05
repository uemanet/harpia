@extends('layouts.modulos.rh')

@section('title')
    Período Aquisitivo
@stop

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
    <link rel="stylesheet" href="{{asset('/css/plugins/datepicker3.css')}}">
@endsection

@section('subtitle')
    Alterar titulação :: {{$periodo_aquisitivo->atc_titulo}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de Edição de Período Aquisitivo</h3>
        </div>
        <div class="box-body">
            {!! Form::model($periodo_aquisitivo,["route" => ['rh.colaboradores.periodosaquisitivos.edit',$periodo_aquisitivo->paq_id], "method" => "PUT", "id" => "form", "role" => "form"]) !!}
            {{ Form::hidden('atc_col_id', $periodo_aquisitivo->colaborador->col_id) }}
            @include('RH::periodosaquisitivos.includes.formulario')
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
