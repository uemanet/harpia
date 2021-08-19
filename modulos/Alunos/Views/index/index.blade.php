@extends('layouts.modulos.alunos')

@section('title')
    Portal do Aluno
@stop

@section('subtitle')
    Portal do Aluno
@stop

@section('content')

    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="ion ion-ios-gear-outline"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Coeficiente de Rendimento</span>
                    <span class="info-box-number">8.58</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-google-plus"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Likes</span>
                    <span class="info-box-number">41,410</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="ion ion-ios-cart-outline"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Sales</span>
                    <span class="info-box-number">760</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">New Members</span>
                    <span class="info-box-number">2,000</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- Matriculas -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Cursos Matriculados</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    @if(!$aluno->matriculas->isEmpty())
                        <div class="box-group" id="accordion">
                            @foreach($aluno->matriculas as $matricula)
                                @php
                                    $situacaoArray = $situacao;
                                    unset($situacaoArray[$matricula->mat_situacao]);
                                @endphp
                                <div class=" box box-success">
                                    <div class="box-header with-border">
                                        <h4 class="box-title">
                                            <a data-toggle="collapse" data-parent="#accordion"
                                               href="#collapse{{$loop->index}}">
                                                {{ $matricula->turma->ofertacurso->curso->crs_nome }}
                                            </a>
                                        </h4>
                                        @if($matricula->mat_situacao == 'cursando')
                                            <span class="label label-info pull-right">Cursando</span>
                                        @elseif($matricula->mat_situacao == 'reprovado')
                                            <span class="label label-danger pull-right">Reprovado</span>
                                        @elseif($matricula->mat_situacao == 'concluido')
                                            <span class="label label-success pull-right">Concluído</span>
                                        @else
                                            <span class="label label-warning pull-right">{{ucfirst($matricula->mat_situacao)}}</span>
                                        @endif
                                    </div>
                                    <div class="panel-collapse collapse in" id="collapse{{ $loop->index }}">
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-12 col-sm-6 col-xs-3">
                                                    <div class="box box-solid">
                                                        <div class="box-header with-border">
                                                            <h3 class="box-title">Informações do Curso</h3>
                                                            <div class="box-tools pull-right">
                                                                <button type="button" class="btn btn-box-tool"
                                                                        data-widget="collapse"><i
                                                                            class="fa fa-minus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="box-body">
                                                            <div class="col-md-4">
                                                                <p><strong>Nível do
                                                                        Curso:</strong> {{ $matricula->turma->ofertacurso->curso->nivelcurso->nvc_nome }}
                                                                </p>
                                                                <p>
                                                                    <strong>Modalidade:</strong> {{ $matricula->turma->ofertacurso->modalidade->mdl_nome }}
                                                                </p>
                                                                <p><strong>Modo de
                                                                        Entrada:</strong> {{ $matricula->mat_modo_entrada }}
                                                                </p>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <p><strong>Oferta de
                                                                        Curso:</strong> {{$matricula->turma->ofertacurso->ofc_ano}}
                                                                </p>
                                                                <p>
                                                                    <strong>Turma:</strong> {{$matricula->turma->trm_nome}}
                                                                </p>
                                                                <p><strong>Polo:</strong> {{$matricula->polo->pol_nome}}
                                                                </p>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <p>
                                                                    <strong>Grupo:</strong> @if($matricula->grupo) {{$matricula->grupo->grp_nome}} @else
                                                                        Sem Grupo @endif</p>
                                                                @if($matricula->mat_situacao == 'concluido')
                                                                    <p><strong>Data de
                                                                            Conclusão:</strong> {{ $matricula->mat_data_conclusao }}
                                                                    </p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="box-footer">
                                                            {!! ActionButton::grid([
                                                                'type' => 'LINE',
                                                                'buttons' => [
                                                                    [
                                                                        'classButton' => 'btn btn-primary pull-right',
                                                                        'icon' => 'fa fa-download',
                                                                        'route' => 'academico.historicoparcial.print',
                                                                        'parameters' => ['id' => $matricula->mat_id],
                                                                        'label' => 'Histórico Parcial',
                                                                        'method' => 'get',
                                                                        'attributes' => [
                                                                            'target' => '_blank',
                                                                        ],
                                                                    ],
                                                                     [
                                                                        'classButton' => 'btn btn-success pull-right',
                                                                        'icon' => 'fa fa-download',
                                                                        'route' => 'alunos.comprovante.matricula',
                                                                        'parameters' => ['id' => $matricula->mat_id],
                                                                        'label' => 'Comprovante de Matrícula',
                                                                        'method' => 'get',
                                                                        'attributes' => [
                                                                            'target' => '_blank',
                                                                        ],
                                                                    ],
                                                                ]
                                                            ])
                                                            !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>Aluno não possui nenhuma matrícula</p>
                    @endif
                </div>
            </div>
        </div>
    </div>


@stop
