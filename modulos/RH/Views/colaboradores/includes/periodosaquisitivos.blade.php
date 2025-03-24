<!-- Períodos Aquisitivos -->
<div class="row">
    <div class="col-md-12">
        <!-- About Me Box -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Períodos Aquisitivos</h3>
                {{--                <span data-toggle="tooltip" class="badge bg-blue">{{$periodo['dias']}} dias Adquiridos</span>--}}
                {{--                <span data-toggle="tooltip" class="badge bg-green">{{$periodo['inicio']}} a {{$periodo['fim']}} </span>--}}
                {{--                <span data-toggle="tooltip" class="badge bg-green">{{$periodo['fim']}} </span>--}}
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @foreach($periodos_matriculas as $periodos)
                    <p style="font-size: medium">
                        <b>Matricula Id</b> : {{$periodos['matricula']->mtc_id}} -
                        <b>Início</b> : {{$periodos['matricula']->mtc_data_inicio}}
                        @if($periodos['matricula']->mtc_data_fim)
                            <b>Fim</b> :  {{$periodos['matricula']->mtc_data_fim}}
                        @endif
                    </p>
                    @if(count($periodos['data']))
                        @foreach($periodos['data'] as $periodo)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box box">
                                        <span data-toggle="tooltip" class="badge bg-gray">Período adquirido: {{$periodo['inicio_adquirido']}} a {{$periodo['fim_adquirido']}}</span>
                                        <span data-toggle="tooltip" class="badge bg-gray"> Limite para gozo: {{$periodo['limite_gozo']}} </span>
                                        <span data-toggle="tooltip" class="badge bg-gray"> Saldo para gozo: {{$periodo['saldo_periodo']}} </span>
                                        <p><b> </b></p>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th style="width: 20%">Início</th>
                                                <th style="width: 20%">Fim</th>
                                                <th style="width: 20%">Observação</th>
                                                <th style="width: 10%">Férias gozadas</th>
                                                <th style="width: 15%"></th>
                                            </tr>
                                            @foreach($periodo['periodo']->periodos_gozo as $periodoGozo)
                                                <tr>
                                                    <td>{{$periodoGozo->pgz_data_inicio}}</td>
                                                    <td>{{$periodoGozo->pgz_data_fim}}</td>
                                                    <td>{{$periodoGozo->pgz_observacao}}</td>
                                                    <td>@if($periodoGozo->pgz_ferias_gozadas)
                                                            Sim
                                                        @elseif(!$periodoGozo->pgz_ferias_gozadas)
                                                            Não
                                                        @endif</td>
                                                    <td>
                                                        @if(!$periodoGozo->pgz_ferias_gozadas)
                                                            {!! ActionButton::grid([
                                                            'type' => 'LINE',
                                                            'buttons' => [
                                                               [
                                                                   'classButton' => 'btn btn-primary btn-sm',
                                                                   'icon' => 'fa fa-pencil',
                                                                   'route' => 'rh.colaboradores.periodosgozo.edit',
                                                                   'parameters' => ['id' => $periodoGozo->pgz_id],
                                                                   'label' => '',
                                                                   'method' => 'get'
                                                               ],
                                                               [
                                                                   'classButton' => 'btn-delete btn btn-danger btn-sm',
                                                                   'icon' => 'fa fa-trash',
                                                                   'route' => 'rh.colaboradores.periodosgozo.delete',
                                                                   'id' => $periodoGozo->pgz_id,
                                                                   'label' => '',
                                                                   'method' => 'post'
                                                               ],
                                                               [
                                                                   'classButton' => 'btn btn-success btn-sm',
                                                                   'icon' => 'fa fa-check',
                                                                   'route' => 'rh.colaboradores.periodosgozo.confirm',
                                                                   'parameters' => ['id' => $periodoGozo->pgz_id],
                                                                   'id' => $periodoGozo->pgz_id,
                                                                   'label' => '',
                                                                   'method' => 'post'
                                                               ]
                                                           ]
                                                   ]) !!}
                                                        @endif

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                                <!-- /.box-tools -->
                            </div>
                        @endforeach
                    @else
                        <p>Colaborador não adquiriu períodos nessa matrícula</p>
                    @endif
                @endforeach
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                {!! ActionButton::grid([
                    'type' => 'LINE',
                    'buttons' => [
                        [
                            'classButton' => 'btn btn-primary',
                            'icon' => 'fa fa-plus-square',
                            'route' => 'rh.colaboradores.periodosgozo.create',
                            'parameters' => ['id' => $colaborador->col_id],
                            'label' => ' Cadastrar Férias',
                            'method' => 'get'
                        ],
                    ]
                ]) !!}
            </div>
        </div>
        <!-- /.box -->
    </div>
</div>

@section('scripts')
    <script type="text/javascript">
        $(document).on('click', '.btn-success', function (event) {
            event.preventDefault();

            var button = $(this);

            swal({
                title: "Tem certeza que deseja confirmar as férias do colaborador?",
                text: "Essa alteração é irreversível!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
                closeOnConfirm: true
            }, function(isConfirm){
                if (isConfirm) {
                    button.closest("form").submit();
                }
            });
        });
    </script>
@endsection
