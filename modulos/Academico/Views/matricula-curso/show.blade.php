@extends('layouts.modulos.academico')

@section('title', 'Informações do Aluno')

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
                    <div class="col-md-4">
                        <p><strong>Nome Completo: </strong> {{$pessoa->pes_nome}}</p>
                        <p><strong>Email: </strong> {{$pessoa->pes_email}}</p>
                        <p><strong>Sexo: </strong> {{($pessoa->pes_sexo == 'M') ? 'Masculino' : 'Feminino' }}</p>
                        <p><strong>Data de Nascimento: </strong> {{$pessoa->pes_nascimento}}</p>
                        <p><strong>Telefone: </strong> {{Format::mask($pessoa->pes_telefone, '(##) #####-####')}}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Nome da Mãe: </strong> {{$pessoa->pes_mae}}</p>
                        <p><strong>Nome do Pai: </strong> {{$pessoa->pes_pai}}</p>
                        <p><strong>Estado Civil: </strong> {{ucfirst($pessoa->pes_estado_civil)}}</p>
                        <p><strong>Naturalidade: </strong> {{$pessoa->pes_naturalidade}}</p>
                        <p><strong>Nacionalidade: </strong> {{$pessoa->pes_nacionalidade}}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Raça: </strong> {{ucfirst($pessoa->pes_raca)}}</p>
                        <p><strong>Necessidade Especial: </strong> {{($pessoa->pes_necessidade_especial == 'S') ? 'Sim' : 'Não'}}</p>
                        <p><strong>Estrangeiro: </strong> {{($pessoa->pes_estrangeiro) ? 'Sim' : 'Não'}}</p>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

    <!-- Matriculas -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Cursos Matriculados</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    @if(!$aluno->matriculas->isEmpty())
                        <table class="table table-bordered">
                            <tr>
                                <th>Curso</th>
                                <th>Oferta de Curso</th>
                                <th>Turma</th>
                                <th>Polo</th>
                                <th>Grupo</th>
                            </tr>
                            @foreach($aluno->matriculas as $matricula)
                                <tr>
                                    <td>{{$matricula->turma->ofertacurso->curso->crs_nome}}</td>
                                    <td>{{$matricula->turma->ofertacurso->ofc_ano}}</td>
                                    <td>{{$matricula->turma->trm_nome}}</td>
                                    <td>@if(!is_null($matricula->polo)) {{$matricula->polo->pol_nome}} @endif</td>
                                    <td>@if(!is_null($matricula->grupo)) {{$matricula->grupo->grp_nome}} @endif</td>
                                </tr>
                            @endforeach
                        </table>
                    @else
                        <p>Aluno não possui nenhuma matrícula</p>
                    @endif
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    {!! ActionButton::grid([
                        'type' => 'LINE',
                        'buttons' => [
                            [
                                'classButton' => 'btn btn-primary',
                                'icon' => 'fa fa-plus-square',
                                'action' => '/academico/matricularalunocurso/create/' . $aluno->alu_id,
                                'label' => ' Nova Matrícula',
                                'method' => 'get'
                            ],
                        ]
                    ]) !!}
                </div>
            </div>
        </div>
    </div>

@endsection