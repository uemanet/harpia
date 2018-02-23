@extends('layouts.modulos.monitoramento')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
    <link rel="stylesheet" href="{{asset('/css/plugins/datepicker3.css')}}">
@endsection

@section('title')
    Tempo Online
@stop

@section('subtitle')
    {{$ambiente->amb_nome}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Visualização de dados do ambiente virtual</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["url" => url('/') . "/monitoramento/ambientesvirtuais/create", "method" => "POST", "id" => "form", "role" => "form"]) !!}
            @include('Monitoramento::tempoonline.includes.formulario')
            {!! Form::close() !!}
        </div>
        <div class="text-center margin" id="grafico"></div>
    </div>
@stop

@section('scripts')
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/plugins/bootstrap-datepicker.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/plugins/bootstrap-datepicker.pt-BR.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("select").select2();
        });
    </script>

    <script type="text/javascript">
        $('.datepicker2').datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR'
        });
        var myDate = new Date();
        var prettyDate = (myDate.getDate() - 7) + '/' + (myDate.getMonth() + 1) + '/' + (myDate.getFullYear());
        $(".datepicker2").datepicker('setDate', prettyDate);
    </script>

    <script type="text/javascript">
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR'
        });

        $(".datepicker").datepicker('setDate', new Date());
    </script>

    <script src="{{asset('/js/plugins/Chart.min.js')}}" type="text/javascript"></script>
@endsection
