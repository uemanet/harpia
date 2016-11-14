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

<!-- Documentos -->
<div class="row">
    <div class="col-md-12">
        <!-- About Me Box -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Documentos</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @if(!$pessoa->documentos->isEmpty())
                    <table class="table table-bordered">
                        <tr>
                            <th>Tipo</th>
                            <th>Numero do Documento</th>
                            <th>Orgão Emissor</th>
                            <th>Data de Emissão</th>
                            <th>Observação</th>
                        </tr>
                        @foreach($pessoa->documentos as $documento)
                            <tr>
                                <td>{{$documento->tipo_documento->tpd_nome}}</td>
                                @if($documento->tipo_documento->tpd_id == 2)
                                    <td>{{Format::mask($documento->doc_conteudo, '###.###.###-##')}}</td>
                                @else
                                    <td>{{$documento->doc_conteudo}}</td>
                                @endif
                                <td>{{$documento->doc_orgao}}</td>
                                <td>{{Format::formatDate($documento->doc_dataexpedicao, 'd/m/Y')}}</td>
                                <td>{{$documento->doc_observacao}}</td>
                            </tr>
                        @endforeach
                    </table>
                @else
                    <p>Sem documentos para apresentar</p>
                @endif
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>

<!-- Titulações -->
<div class="row">
    <div class="col-md-12">
        <!-- About Me Box -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Titulações</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                @if(!$pessoa->titulacoes_informacoes->isEmpty())
                    <table class="table table-bordered">
                        <tr>
                            <th>Nome</th>
                            <th>Titulo</th>
                            <th>Instituição</th>
                            <th>Data de Conclusão</th>
                        </tr>
                        @foreach($pessoa->titulacoes_informacoes as $titulacao)
                            <tr>
                                <td>{{$titulacao->titulacao->tit_nome}}</td>
                                <td>{{$titulacao->tin_titulo}}</td>
                                <td>{{$titulacao->tin_instituicao}}</td>
                                <td>{{Format::formatDate($titulacao->tin_anofim, 'd/m/Y')}}</td>
                            </tr>
                        @endforeach
                    </table>
                @else
                    <p>Sem titulações para apresentar</p>
                @endif
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>