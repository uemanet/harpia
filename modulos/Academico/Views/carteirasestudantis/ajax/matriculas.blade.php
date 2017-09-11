<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Inclusão de Matrículas - {{$lista->lst_nome}}</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
        <!-- /.box-tools -->
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                @if ($matriculasLista->count() || $matriculasOutLista->count())
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab1" data-toggle="tab">
                                    Matrículas Fora da Lista
                                    <span data-toggle="tooltip" class="badge bg-blue">{{$matriculasOutLista->count()}}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#tab2" data-toggle="tab">
                                    Matriculas na Lista
                                    <span data-toggle="tooltip" class="badge bg-blue">{{$matriculasLista->count()}}</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab1">
                                @if ($matriculasOutLista->count())
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-bordered table-striped">
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
                                                        <th>
                                                            Turma
                                                        </th>
                                                        <th>
                                                            Polo
                                                        </th>
                                                        <th>
                                                            Status
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($matriculasOutLista as $matricula)
                                                    <tr>
                                                        <td>
                                                            @if($matricula->apto)
                                                                <label>
                                                                    <input class="matriculas" type="checkbox" value="{{$matricula->mat_id}}">
                                                                </label>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{$matricula->pes_nome}}
                                                        </td>
                                                        <td>
                                                            {{$matricula->trm_nome}}
                                                        </td>
                                                        <td>
                                                            {{$matricula->pol_nome}}
                                                        </td>
                                                        <td>
                                                            @if($matricula->apto)
                                                                <span class="label label-success">
                                                                    Apto
                                                                </span>
                                                            @else
                                                                <span class="label label-warning">
                                                                    Faltando informações pessoais e/ou documentos
                                                                </span>
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
                                            <button class="btn btn-success hidden btnIncluir">Incluir Matriculas</button>
                                        </div>
                                    </div>
                                @else
                                    <p>Sem matrículas para incluir</p>
                                @endif
                            </div>
                            <div class="tab-pane" id="tab2">
                                <div class="row">
                                    <div class="col-md-12">
                                        @if ($matriculasLista->count())
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                <tr>
                                                    <th>
                                                        Nome
                                                    </th>
                                                    <th>
                                                        Turma
                                                    </th>
                                                    <th>
                                                        Polo
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($matriculasLista as $matricula)
                                                    <tr>
                                                        <td>{{ $matricula->pes_nome }}</td>
                                                        <td>
                                                            {{$matricula->trm_nome}}
                                                        </td>
                                                        <td>
                                                            {{$matricula->pol_nome}}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <p>Não há matrículas inclusas na lista</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <p>Não há matrículas aptas para a inclusão</p>
                @endif
            </div>
        </div>
    </div>
</div>