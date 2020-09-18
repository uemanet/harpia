@extends('layouts.modulos.rh')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
    <link rel="stylesheet" href="{{asset('/css/plugins/datepicker3.css')}}">
@endsection

@section('title')
    Colaboradores
@stop

@section('subtitle')
    Alterar Colaborador :: {{$colaborador->pessoa->pes_nome}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Alteração de Setor e Função de Colaboradores</h3>
        </div>
        <div class="box-body">
            {!! Form::model($colaborador,['route' => ['rh.colaboradores.movimentacaosetor', $colaborador->col_id], "method" => "PUT", "id" => "form", "role" => "form"]) !!}


            @include('RH::colaboradores.includes.formulario_movimentacao_setor', ['colaborador' => $colaborador])

            <div class="row">
                <div class="form-group col-md-12">
                    {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
                </div>
            </div>
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

@endsection


