@extends('layouts.modulos.integracao')

@section('title')
    Lista de Alunos
@stop

@section('subtitle')
    Disciplina: {{ $ofertaDisciplina->moduloDisciplina->disciplina->dis_nome }}
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-filter"></i> Filtrar dados</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <form method="GET" action="{{ route('integracao.mapeamentonotas.showalunos', $ofertaDisciplina->ofd_id) }}">
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="pes_cpf" id="pes_cpf" value="{{Request::input('pes_cpf')}}" placeholder="CPF">
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="pes_nome" id="pes_nome" value="{{Request::input('pes_nome')}}" placeholder="Nome">
                            </div>
                            <div class="col-md-3">
                                <input type="email" class="form-control" name="pes_email" id="pes_email" value="{{Request::input('pes_email')}}" placeholder="Email">
                            </div>
                            <div class="col-md-3">
                                <input type="submit" class="form-control btn-primary" value="Buscar">
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @if(!is_null($tabela))
                <div class="box box-primary">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-3 pull-right">
                                <div class="form-group">
                                    <button class="btn btn-success form-control btn-mapear" data-id="{{ $ofertaDisciplina->ofd_id }}">
                                        <i class="fa fa-exchange"></i> Migrar Todas as Notas
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                {!! $tabela->render() !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center">{!! $paginacao->links() !!}</div>
            @else
                <div class="box box-primary">
                    <div class="box-body">Sem registros para apresentar</div>
                </div>
            @endif
        </div>
    </div>
@stop

@section('scripts')
    <script>
        $(function() {
            $(document).on('click', '.btn-mapear', function (event) {
                var ofd_id = $(event.currentTarget).data('id');

                $.harpia.showloading();

                $.ajax({
                    type: 'GET',
                    url: "/integracao/async/mapeamentonotas/"+ofd_id+"/mapearnotasalunos",
                    success: function (response) {
                        $.harpia.hideloading();

                        var msg = response.msg;

                        toastr.success(msg, null, {timeOut: 3000, progressBar: true});

                        setTimeout(function(){ location.reload(); }, 3000);
                    },
                    error: function (response) {
                        $.harpia.hideloading();

                        var msg = response.responseJSON.error;

                        toastr.error(msg, null, {progressBar: true});
                    }
                });
            });
        });
    </script>
@stop
