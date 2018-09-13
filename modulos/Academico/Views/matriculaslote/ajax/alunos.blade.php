<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Lista de Alunos</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
        <!-- /.box-tools -->
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                @if (!empty($matriculas))
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab1" data-toggle="tab">
                                    Não Matriculados
                                    <span data-toggle="tooltip" class="badge bg-blue">{{count($matriculas['nao_matriculados'])}}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#tab2" data-toggle="tab">
                                    Cursando
                                    <span data-toggle="tooltip" class="badge bg-blue">{{count($matriculas['cursando'])}}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#tab3" data-toggle="tab">
                                    Aprovados
                                    <span data-toggle="tooltip" class="badge bg-blue">{{count($matriculas['aprovados'])}}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#tab4" data-toggle="tab">
                                    Reprovados
                                    <span data-toggle="tooltip" class="badge bg-blue">{{count($matriculas['reprovados'])}}</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab1">
                                @if (!empty($matriculas['nao_matriculados']))
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-matricular table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th width="1%">
                                                            <label>
                                                                <input id="select_all" type="checkbox">
                                                            </label>
                                                        </th>
                                                        <th>
                                                            Nome
                                                        </th>
                                                        <th width="20%">
                                                            Situação de Matrícula
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($matriculas['nao_matriculados'] as $matricula)
                                                        <tr>
                                                            <td>
                                                                @if ($matricula->status == 'apto')
                                                                    <label>
                                                                        <input class="matriculas" type="checkbox" value="{{$matricula->mat_id}}">
                                                                    </label>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                {{$matricula->aluno->pessoa->pes_nome}}
                                                            </td>
                                                            <td>
                                                                @if($matricula->status == 'apto')
                                                                    <span class="label label-success">Apto para Matricula</span>
                                                                @else
                                                                    <span class="label label-warning">Pré-requisitos não satisfeitos</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button class="btn btn-success hidden btnMatricular">Matricular Alunos</button>
                                        </div>
                                    </div>
                                @else
                                    <p>Sem alunos para matricular</p>
                                @endif
                            </div>
                            <div class="tab-pane" id="tab2">
                                <div class="row">
                                    <div class="col-md-12">
                                        @if (!empty($matriculas['cursando']))
                                            <table class="table table-desmatricular table-striped table-bordered">
                                                <thead>
                                                    <th width="1%">
                                                          <label>
                                                              <input id="select_all_matriculados" type="checkbox">
                                                          </label>
                                                    </th>
                                                    <th>Nome</th>
                                                    <th width="20%">Situação Matrícula</th>
                                                </thead>
                                                <tbody>
                                                    @foreach($matriculas['cursando'] as $matricula)
                                                        <tr>
                                                           <td>
                                                                @if ($matricula->status == 'apto')
                                                                    <label>
                                                                        <input class="matriculados" type="checkbox" value="{{$matricula->mat_id}}">
                                                                    </label>
                                                                @endif
                                                            </td>
                                                            <td>{{ $matricula->aluno->pessoa->pes_nome }}</td>
                                                            <td>
                                                                <span class="label label-info">Cursando</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <p>Não há alunos cursando a disciplina</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                      <div class="col-md-12">
                                          <button class="btn btn-danger hidden btnDesmatricular">Desmatricular Alunos</button>
                                      </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab3">
                                <div class="row">
                                    <div class="col-md-12">
                                        @if (!empty($matriculas['aprovados']))
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <th>Nome</th>
                                                    <th width="20%">Situação Matrícula</th>
                                                </thead>
                                                <tbody>
                                                @foreach($matriculas['aprovados'] as $matricula)
                                                    <tr>
                                                        <td>{{ $matricula->aluno->pessoa->pes_nome }}</td>
                                                        <td>
                                                            <span class="label label-success">Aprovado</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <p>Não há alunos aprovados nessa disciplina</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab4">
                                <div class="row">
                                    <div class="col-md-12">
                                        @if (!empty($matriculas['reprovados']))
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                <th>Nome</th>
                                                <th width="20%">Situação Matrícula</th>
                                                </thead>
                                                <tbody>
                                                @foreach($matriculas['reprovados'] as $matricula)
                                                    <tr>
                                                        <td>{{ $matricula->aluno->pessoa->pes_nome }}</td>
                                                        <td>
                                                            <span class="label label-danger">Reprovado</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <p>Não há alunos reprovados nessa disciplina</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <p>Não há alunos matriculados na turma/polo</p>
                @endif
            </div>
        </div>
    </div>
</div>
