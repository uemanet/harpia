@extends('layouts.modulos.integracao')

@section('title')
    Sincronização
@stop

@section('subtitle')
    Detalhes de evento de sincronização
@stop

@section('content')
    <!--  Dados Pessoais  -->
    <div class="row">
        <div class="col-md-12">
            <!-- About Me Box -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><strong>Ação:</strong> {{ ucfirst(strtolower($sincronizacao->sym_action)) }}</h3>
                    <div class="box-tools pull-right">
                        @if($sincronizacao->sym_status == 1)
                            <span class="label label-info">Pendente</span>
                        @elseif($sincronizacao->sym_status == 2)
                            <span class="label label-success">Sucesso</span>
                        @elseif($sincronizacao->sym_status == 3)
                            <span class="label label-danger">Falha</span>
                        @endif
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Data: </strong> {{ date('d/m/Y', strtotime($sincronizacao->sym_data_envio)) }}</p>
                            <p><strong>Hora: </strong> {{ date('H:i:s', strtotime($sincronizacao->sym_data_envio)) }}</p>
                            <p><strong>Mensagem: </strong> {{ $sincronizacao->sym_mensagem }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Tabela: </strong> {{ $sincronizacao->sym_table }}</p>
                            <p><strong>ID: </strong> {{ $sincronizacao->sym_table_id }}</p>
                            <p><strong>Extra: </strong> {{ $sincronizacao->sym_extra }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box -->
        </div>
    </div>
@stop