@extends('layouts.modulos.rh')

@section('stylesheets')
    <style>
        .title-box {
            display: inline-block;
            font-size: 18px;
            margin: 0;
            line-height: 1;
            font-family: 'Source Sans Pro', sans-serif;
        }
    </style>
@stop

@section('title', 'Informações da Pessoa')

@section('content')

<!--  Dados Pessoais  -->
<div class="row">
    <div class="col-md-12">
        <!-- About Me Box -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Dados Pessoais</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-4">
                        <p><strong>Id: </strong> {{$justificativa->jus_id}}</p>
                        <p><strong>Período Laboral: </strong> {{$justificativa->horaTrabalhada->periodo->pel_inicio}} a  {{$justificativa->horaTrabalhada->periodo->pel_termino}} </p>
                        <p><strong>Descrição: </strong> {{$justificativa->jus_descricao}}</p>
                        <p><strong>Data Inicial justificada: </strong> {{$justificativa->jus_data}}</p>
                        <p><strong>Data Final justificada: </strong> {{$justificativa->jus_data_fim}}</p>
                        <p><strong>Quantidade de horas: </strong> {{$justificativa->jus_horas}}</p>

                        @if(!is_null($justificativa->jus_anx_id))
                            <?php $botoes[] =  [
                                'classButton' => 'btn btn-success btn-sm docAnexo',
                                'icon' => 'fa fa-download',
                                'route' => 'rh.horastrabalhadas.justificativas.anexo',
                                'parameters' => ['id' => $justificativa->jus_id],
                                'label' => 'Baixar anexo',
                                'method' => 'get'
                            ];
                            ?>

                            {!! ActionButton::grid([
                                   'type' => 'LINE',
                                   'buttons' => $botoes
                           ]) !!}
                        @endif


                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>
@endsection