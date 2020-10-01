@extends('layouts.modulos.rh')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
    <link rel="stylesheet" href="{{asset('/css/plugins/datepicker3.css')}}">
@endsection

@section('title')
    Salário Base
@stop

@section('subtitle')
    Cadastro Salário Base
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de Salário Base</h3>
        </div>
        <div class="box-body">
            {!! Form::open(['route' => ['rh.fontespagadoras.vinculosfontespagadoras.create',  $fonte_pagadora->fpg_id], "method" => "POST", "id" => "form", "role" => "form"]) !!}
            {{ Form::hidden('vfp_fpg_id', $fonte_pagadora->fpg_id) }}
            @include('RH::vinculosfontespagadoras.includes.formulario')
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


    <script>
        $(document).ready(function() {


            $("select").select2();

            $('#vfp_vin_id').change(function() {
                var value = $(this).val();

                if (value == 2) {
                    document.getElementById("vfp_unidade").disabled = true;
                    document.getElementById("vfp_valor").readOnly = true;
                } else {
                    document.getElementById("vfp_unidade").disabled = false;
                    document.getElementById("vfp_valor").readOnly = false;
                }
            });
        });


        function k(i) {
            var v = i.value.replace(/\D/g,'');
            v = (v/100).toFixed(2) + '';
            v = v.replace(".", ".");
            v = v.replace(/(\d)(\d{3})(\d{3}),/g, "$1.$2.$3,");
            v = v.replace(/(\d)(\d{3}),/g, "$1.$2,");
            i.value = v;
        }



    </script>




@endsection


