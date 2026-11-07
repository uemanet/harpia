<div class="card card-primary card-outline p-0">
    <div class="card-header with-border">
        <h3 class="card-title">
            Matrículas - {{$turma->trm_nome}}
        </h3>
        <div class="card-tools pull-right">
            <button type="button" class="btn btn-card-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        @if($matriculas->count())
            <div class="row">
                <div class="col-md-12">
                    <p class="pull-right">
                        <a href="{{route('academico.carteirasestudantis.print', ['lista' => $lista->lst_id, 'turma' => $turma->trm_id])}}" class="btn btn-primary" target="_blank">
                            <i class="fa fa-print"></i> Imprimir Lista
                        </a>
                        <a href="{{route('academico.carteirasestudantis.exportfile', ['lista' => $lista->lst_id, 'turma' => $turma->trm_id])}}" class="btn btn-success">
                            <i class="fa fa-download"></i> Exportar Arquivo
                        </a>
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <th>Nome</th>
                            <th>Turma</th>
                            <th>Polo</th>
                            <th width="5%">Ações</th>
                        </thead>
                        <tbody>
                            @foreach($matriculas as $matricula)
                                <tr>
                                    <td>{{$matricula->pes_nome}}</td>
                                    <td>{{$matricula->trm_nome}}</td>
                                    <td>{{$matricula->pol_nome}}</td>
                                    <td>
                                        @haspermission('academico.carteirasestudantis.deletematricula')
                                            <button class="btn btn-danger btnDelete" data-mat-id="{{$matricula->mat_id}}"><i class="fa fa-trash"></i></button>
                                        @endhaspermission
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>