@extends('layouts.modulos.default')

@section('title')
    Salário Base
@stop

@section('subtitle')
    Cadastro Salário Base
@stop

@section('content')
    <div class="card card-success card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de cadastro de Salário Base</h3>
        </div>
        <form action="{{ route('rh.fontespagadoras.vinculosfontespagadoras.create', [$fonte_pagadora->fpg_id]) }}" method="POST" id="form" role="form">
            @csrf
            <div class="card-body">
                <input type="hidden" name="vfp_fpg_id" value="{{ $fonte_pagadora->fpg_id }}" >
                @include('RH::vinculosfontespagadoras.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop

@section('scripts')
    <script>
        $(document).ready(function() {
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