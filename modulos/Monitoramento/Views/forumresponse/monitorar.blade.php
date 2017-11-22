@extends('layouts.modulos.monitoramento')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
    <link rel="stylesheet" href="{{asset('/css/plugins/datepicker3.css')}}">
@endsection

@section('title')
    Monitoramento de Respostas nos Fóruns
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
            @include('Monitoramento::forumresponse.includes.formulario')
        </div>
        <div class="text-center margin" id="grafico"></div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary hidden" id="boxTutores">
                <!-- /.box-header -->
                <div class="box-body">

                </div>
            </div>
        </div>
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

    <script src="{{asset('/js/plugins/Chart.min.js')}}" type="text/javascript"></script>
@endsection
