<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Matrículas</h3>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
        <!-- /.box-tools -->
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                @if($matriculas->count())
                    @if($tipoNota == "Numérica")
                        @include('Academico::lancamentonotas.ajax.table_numerica')
                    @endif

                    @if($tipoNota == "Conceitual")
                        @include('Academico::lancamentonotas.ajax.table_conceitual')
                    @endif
                @else
                    <p>Não há matrículas nesta oferta de disciplina</p>
                @endif
            </div>
        </div>
    </div>
    <!-- /.box-body -->
</div>