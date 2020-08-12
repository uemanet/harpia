@extends('layouts.modulos.rh')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
    <link rel="stylesheet" href="{{asset('/css/plugins/datepicker3.css')}}">
@endsection

@section('title')
    Contas de Colaborador
@stop

@section('subtitle')
    Alterar conta de colaborador :: {{$conta_colaborador->ccb_conta}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de Edição de Conta de Colaborador</h3>
        </div>
        <div class="box-body">
            {!! Form::model($conta_colaborador,["route" => ['rh.colaboradores.contascolaboradores.edit',$conta_colaborador->ccb_id], "method" => "PUT", "id" => "form", "role" => "form"]) !!}
            {{ Form::hidden('ccb_col_id', $conta_colaborador->colaborador->col_id) }}
            @include('RH::contascolaboradores.includes.formulario')
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
