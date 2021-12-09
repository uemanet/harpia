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
                    <h3 class="box-title"><strong>Ação:</strong> {{ ucfirst(strtolower($sincronizacao->sym_action)) }}
                    </h3>
                    <div class="box-tools pull-right">
                        @if($sincronizacao->sym_status == 1)
                            <span class="label label-info">Pendente</span>
                        @elseif($sincronizacao->sym_status == 2)
                            <span class="label label-success">Sucesso</span>
                        @elseif($sincronizacao->sym_status == 3)
                            <span class="label label-danger">Falha</span>
                        @endif
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            @if($sincronizacao->sym_data_envio)
                                <p>
                                    <strong>Data: </strong> {{ date('d/m/Y', strtotime($sincronizacao->sym_data_envio)) }}
                                </p>
                                <p>
                                    <strong>Hora: </strong> {{ date('H:i:s', strtotime($sincronizacao->sym_data_envio)) }}
                                </p>
                            @else
                                <p><strong>Data: </strong> - </p>
                                <p><strong>Hora: </strong> - </p>
                            @endif
                            <p><strong>Mensagem: </strong> {{ $sincronizacao->sym_mensagem }}</p>
                            <p><strong>Versão de Integração: </strong> {{ $sincronizacao->sym_version }}</p>
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

    @if($sincronizacao->sym_status == 3 and (
            $sincronizacao->sym_table === 'acd_ofertas_disciplinas' OR
            $sincronizacao->sym_table === 'acd_matriculas' OR
            $sincronizacao->sym_table === 'acd_tutores_grupos'
        )
    )
        <!--  Dados Pessoais  -->
        <div class="row">
            <div class="col-md-12">
                <!-- About Me Box -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <strong>Dados de migração</strong>
                        </h3>
                        @if($user->mapped)
                            <span class="label label-success">Usuário mapeado</span>
                        @else
                            <span class="label label-danger">Usuário não mapeado</span>
                        @endif
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                            </button>
                        </div>
                        <!-- /.box-tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p style="font-size: large"><strong>Dados no Harpia: </strong></p>
                                <p><strong>pes_id: </strong> {{ $pessoa->pes_id }} </p>
                                <p><strong>Nome: </strong> {{ $pessoa->pes_nome }} </p>
                                <p><strong>Email: </strong> {{ $pessoa->pes_email }} </p>
                            </div>
                            <div class="col-md-6">
                                <p style="font-size: large"><strong>Dados no Moodle: </strong></p>
                                <p><strong>id: </strong> {{ $user->id }} </p>
                                <p><strong>Nome: </strong> {{ $user->firstname.' '.$user->lastname }} </p>
                                <p><strong>Email: </strong> {{ $user->email }} </p>
                            </div>
                        </div>
                        <td>
                            @if(!$user->mapped)
                                {!! ActionButton::grid([
                                    'type' => 'LINE',
                                    'buttons' => [
                                        [
                                            'classButton' => 'btn btn-danger btn-delete',
                                            'icon' => 'fa fa-warning',
                                            'route' => 'integracao.sincronizacao.mapear',
                                            'parameters' => ['id' => $sincronizacao->sym_id],
                                            'id' => $sincronizacao->sym_id,
                                            'label' => 'Mapear',
                                            'method' => 'post'
                                        ]
                                    ]
                                ]) !!}
                            @endif
                        </td>
                    </div>
                </div>
                <!-- /.box -->
            </div>
        </div>
    @endif
@stop



@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $("select").select2();
        });

        $(document).on('click', '.btn-delete', function (event) {
            event.preventDefault();

            var button = $(this);

            swal({
                title: "Tem certeza que deseja executar essa operação?",
                text: "Você não poderá alterar essa informação!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Sim, desejo fazer o mapeamento!",
                cancelButtonText: "Não, quero cancelar!",
                closeOnConfirm: true
            }, function(isConfirm){
                if (isConfirm) {
                    button.closest("form").submit();
                }
            });
        });


    </script>


@endsection


