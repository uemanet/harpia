@extends('layouts.modulos.default')

@section('title')
    Logs de Auditoria
@stop

@section('subtitle')
    Módulo de Segurança
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title m-0"><i class="fa fa-filter"></i> Filtrar Logs de Auditoria</h3>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('seguranca.auditoria.index') }}" class="w-100">
                    <div class="row">
                        <div class="col-md-3 px-1 mb-2">
                            <label for="log_table" class="form-label">Tabela</label>
                            <input type="text" class="form-control" name="log_table" id="log_table" value="{{ Request::input('log_table') }}" placeholder="Ex: reh_eventos_acesso">
                        </div>
                        <div class="col-md-2 px-1 mb-2">
                            <label for="log_action" class="form-label">Ação</label>
                            <select name="log_action" id="log_action" class="form-control">
                                <option value="">Todas</option>
                                <option value="INSERT" {{ Request::input('log_action') == 'INSERT' ? 'selected' : '' }}>INSERT</option>
                                <option value="UPDATE" {{ Request::input('log_action') == 'UPDATE' ? 'selected' : '' }}>UPDATE</option>
                                <option value="DELETE" {{ Request::input('log_action') == 'DELETE' ? 'selected' : '' }}>DELETE</option>
                            </select>
                        </div>
                        <div class="col-md-2 px-1 mb-2">
                            <label for="log_table_id" class="form-label">ID do Registro</label>
                            <input type="number" class="form-control" name="log_table_id" id="log_table_id" value="{{ Request::input('log_table_id') }}" placeholder="Ex: 5004">
                        </div>
                        <div class="col-md-2 px-1 mb-2">
                            <label for="data_inicio" class="form-label">Data Início</label>
                            <input type="text" class="form-control datepicker" name="data_inicio" id="data_inicio" value="{{ Request::input('data_inicio') }}">
                        </div>
                        <div class="col-md-2 px-1 mb-2">
                            <label for="data_fim" class="form-label">Data Fim</label>
                            <input type="text" class="form-control datepicker" name="data_fim" id="data_fim" value="{{ Request::input('data_fim') }}">
                        </div>
                        <div class="col-md-1 px-1 mb-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="card card-primary card-outline my-2">
            @if(!is_null($tabela))
                <div class="card-body p-0 table-responsive">
                    {!! $tabela->render() !!}
                </div>
                <div class="card-footer clearfix">
                    {!! $paginacao->links('pagination::bootstrap-4') !!}
                </div>
            @else
                <div class="card-body">
                    Nenhum registro de auditoria encontrado.
                </div>
            @endif
        </div>
    </div>

    <!-- Modal para Exibição do JSON do Objeto Auditado -->
    <div class="modal fade" id="modal-json" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Objeto Auditado (#<span id="modal-log-id"></span>)</h5>
                </div>
                <div class="modal-body">
                    <pre id="json-renderer" class="bg-dark text-light p-3" style="max-height: 450px; overflow-y: auto; border-radius: 4px;"></pre>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    @vite('modulos/Seguranca/Resources/js/pages/auditoria/index.js')
@stop