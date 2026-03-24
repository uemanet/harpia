<div class="card card-primary card-outline">
    <div class="card-header with-border">
        <h3 class="card-title">Matrículas</h3>

        <div class="card-tools pull-right">
            <button type="button" class="btn btn-card-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
        <!-- /.card-tools -->
    </div>
    <!-- /.card-header -->
    <div class="card-body">
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
    <!-- /.card-body -->
</div>