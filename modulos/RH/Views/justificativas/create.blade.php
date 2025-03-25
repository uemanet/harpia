@extends('layouts.modulos.rh')

@section('breadcrumbs')
    {{ Breadcrumbs::render('rh.horastrabalhadas.justificativas.create', $horaTrabalhada->htr_id) }}
@endsection

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
    <link rel="stylesheet" href="{{asset('/css/plugins/datepicker3.css')}}">
@endsection


@section('title')
    Justificativas
@stop

@section('subtitle')
    Cadastro de Justificativa
@stop

@section('details')
    Gerenciamento de Justificativas : {{$horaTrabalhada->colaborador->pessoa->pes_nome}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de justificativas</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["route" => 'rh.horastrabalhadas.justificativas.create', "method" => "POST", "id" => "form", "role" => "form", "enctype" => "multipart/form-data"]) !!}
                @include('RH::justificativas.includes.formulario_create')
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
