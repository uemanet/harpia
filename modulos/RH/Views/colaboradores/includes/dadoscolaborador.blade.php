@section('stylesheets')
    <style>
        .title-box {
            display: inline-block;
            font-size: 18px;
            margin: 0;
            line-height: 1;
            font-family: 'Source Sans Pro', sans-serif;
        }
    </style>
@stop
<!--  Dados Pessoais  -->
<div class="row">
    <div class="col-md-12">
        <!-- About Me Box -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Dados do Colaborador</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-4">
                        <p><strong>Setor: </strong> {{$colaborador->setor->set_descricao}}</p>
                        <p><strong>Data de Admissão: </strong> {{$colaborador->col_data_admissao}}</p>
                        <p><strong>Carga Horária: </strong> {{$colaborador->col_ch_diaria}}</p>
                        <p><strong>Código da catraca: </strong> {{$colaborador->col_codigo_catraca}}</p>

                    </div>
                    <div class="col-md-4">
                        <p><strong>Função: </strong> {{$colaborador->funcao->fun_descricao}}</p>
                        <p><strong>Vínculo com a universidade: </strong> {{($colaborador->col_vinculo_universidade == 1) ? 'Sim' : 'Não' }}</p>
                        <p><strong>Matrícula na universidade: </strong> {{$colaborador->col_matricula_universidade}}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p><strong>Observação: </strong> {{$colaborador->col_observacao}}</p>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>