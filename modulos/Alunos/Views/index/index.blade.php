@extends('layouts.modulos.alunos')

@section('title')
    Portal do Aluno
@stop

@section('subtitle')
    Portal do Aluno
@stop

@section('content')
    <!-- Matriculas -->
    <div class="row">

        <div class="col-md-3">

            <div class="box box-primary">
                <div class="box-body box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="{{ route('seguranca.profile.profile-picture', \Illuminate\Support\Facades\Auth::user()->usr_profile_picture_id ?? 0 ) }}" alt="User profile picture">

                    <h3 class="profile-username text-center">{{$aluno->pessoa->pes_nome}}</h3>

                    <p class="text-muted text-center">Software Engineer</p>

                </div>
                <!-- /.box-body -->
            </div>

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Sobre</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <strong><i class="fa fa-book margin-r-5"></i> Período Letivo Atual</strong>

                    <p class="text-muted">
                        2021.2 - 02/08/2021 a 23/12/2021
                    </p>

                    <hr>

                    <strong><i class="fa fa-map-marker margin-r-5"></i> Meus dados gerais</strong>

                    <p class="text-muted"><strong>Email: </strong> {{$aluno->pessoa->pes_email}}</p>
                    <p class="text-muted"><strong>Telefone: </strong> {{Format::mask($aluno->pessoa->pes_telefone, '(##) #####-####')}}</p>
                    <p class="text-muted"><strong>Sexo: </strong> {{($aluno->pessoa->pes_sexo == 'M') ? 'Masculino' : 'Feminino' }}</p>
                </div>
                <!-- /.box-body -->
            </div>

        </div>

        <div class="col-md-9">
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
                    @if(!$matriculas->isEmpty())
                        <div class="box-group" id="accordion">
                            @foreach($matriculas as $matricula)
                                @php
                                    $situacaoArray = $situacao;
                                    unset($situacaoArray[$matricula->mat_situacao]);
                                @endphp
                                <div class=" box box-success">
                                    <div class="box-header with-border">

                                        <div class="row">
                                            <div class="box-title col-sm-4">
                                                <a data-toggle="collapse" data-parent="#accordion"
                                                   href="#collapse{{$loop->index}}">
                                                    {{ $matricula->turma->ofertacurso->curso->crs_nome }}
                                                </a>
                                            </div>
                                            <div class=" col-sm-4">
                                                <!-- Progress bars -->
                                                <div class="clearfix">
                                                    <span class="pull-left">Progresso no curso</span>
                                                    <small class="label label-info pull-right">{{ $matricula->progress }}%</small>
                                                </div>
                                                <div class="progress xs">
                                                    <div class="progress-bar progress-bar-aqua" style="width: {{ $matricula->progress }}%;"></div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">

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
                                        </div>
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
                                                                <p><strong>Coeficiente de rendimento:</strong> {{ $matricula->coefficient }}
                                                                </p>
                                                                <p><strong>Nível do
                                                                        Curso:</strong> {{ $matricula->turma->ofertacurso->curso->nivelcurso->nvc_nome }}
                                                                </p>
                                                                <p>
                                                                    <strong>Modalidade:</strong> {{ $matricula->turma->ofertacurso->modalidade->mdl_nome }}
                                                                </p>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <p><strong>Modo de
                                                                        Entrada:</strong> {{ $matricula->mat_modo_entrada }}
                                                                </p>
                                                                <p><strong>Oferta de
                                                                        Curso:</strong> {{$matricula->turma->ofertacurso->ofc_ano}}
                                                                </p>
                                                                <p>
                                                                    <strong>Turma:</strong> {{$matricula->turma->trm_nome}}
                                                                </p>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <p><strong>Polo:</strong> {{$matricula->polo->pol_nome}}
                                                                </p>
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
                                                                        'route' => 'alunos.comprovante.historico',
                                                                        'parameters' => ['id' => $matricula->mat_id],
                                                                        'label' => 'Histórico Parcial',
                                                                        'method' => 'get',
                                                                        'attributes' => [
                                                                            'target' => '_blank',
                                                                        ],
                                                                    ],
                                                                     [
                                                                        'classButton' => 'btn btn-primary pull-right',
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
