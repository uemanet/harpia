@extends('layouts.modulos.default')

@section('title')
    Lista de Alunos
@stop

@section('subtitle')
    Disciplina: {{ $ofertaDisciplina->moduloDisciplina->disciplina->dis_nome }}
@stop

@section('actionButton')
    @php
        $turma = $ofertaDisciplina->turma;
    @endphp
    <a href="{{ route('integracao.mapeamentonotas.index', [
        'crs_id' => $turma->ofertacurso->ofc_crs_id,
        'ofc_id' => $turma->trm_ofc_id,
        'trm_id' => $turma->trm_id,
    ]) }}" class="btn btn-secondary">
        <i class="fa fa-arrow-left"></i> Voltar para disciplinas
    </a>
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline p-0">
            <div class="card-header with-border">
                <h3 class="card-title"><i class="fa fa-filter"></i> Filtrar dados</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <form method="GET" action="{{{ route('integracao.mapeamentonotas.showalunos', $ofertaDisciplina->ofd_id) }}}" class="d-flex w-100">
                        <div class="col-md-3 px-1">
                            <input type="text" class="form-control" name="pes_cpf" id="pes_cpf" value="{{Request::input('pes_cpf')}}" placeholder="CPF">
                        </div>
                        <div class="col-md-3 px-1">
                            <input type="text" class="form-control" name="pes_nome" id="pes_nome" value="{{Request::input('pes_nome')}}" placeholder="Nome">
                        </div>
                        <div class="col-md-3 px-1">
                            <input type="email" class="form-control" name="pes_email" id="pes_email" value="{{Request::input('pes_email')}}" placeholder="Email">
                        </div>
                        <div class="col-md-3 px-1">
                            <input type="submit" class="btn btn-primary w-100" value="Buscar">
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>

    <div class="row">
        <div class="card card-primary card-outline my-2 p-0">
            @if(!is_null($tabela))
                <div class="card-header">
                    <div class="row justify-content-end">
                        <div class="col-md-3">
                            <button class="btn btn-success w-100 btn-mapear" data-id="{{ $ofertaDisciplina->ofd_id }}">
                                <i class="fa fa-exchange"></i> Migrar Todas as Notas
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    {!! $tabela->render() !!}
                </div>
                <div class="card-footer clearfix">
                    {!! $paginacao->links('pagination::bootstrap-4') !!}
                </div>
            @else
                <div class="card-body">
                    Sem registros para apresentar
                </div>
            @endif
        </div>
    </div>
@stop

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
