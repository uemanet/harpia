<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">
            Matrículas - {{$turma->trm_nome}}
        </h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
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
                                        <button class="btn btn-danger"><i class="fa fa-trash"></i></button>
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