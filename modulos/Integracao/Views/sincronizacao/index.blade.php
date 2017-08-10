@extends('layouts.modulos.integracao')

@section('title')
    Sincronização
@stop

@section('subtitle')
    Módulo de Integração
@stop

@section('content')
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
                <form method="GET" action="{{ route('integracao.sincronizacao.index') }}">
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="sym_table" id="sym_table" value="{{Input::get('sym_table')}}" placeholder="Nome da tabela">
                    </div>
                    <div class="col-md-3">
                      <select class="form-control" id="sym_status" name="sym_status">
                        <option selected="selected" disabled="disabled" hidden="hidden" value="">Escolha um status</option>
                        <option value="1">Pendente</option>
                        <option value="2">Sucesso</option>
                        <option value="3">Falha</option>
                      </select>
                    </div>
                    <div class="col-md-3">
                        <input type="submit" class="form-control btn-primary" value="Buscar">
                    </div>
                </form>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    @if(!is_null($tabela))
        <div class="box box-primary">
            <div class="box-header">
                {!! $tabela->render() !!}
            </div>
        </div>

        <div class="text-center">{!! $paginacao->links() !!}</div>

    @else
        <div class="box box-primary">
            <div class="box-body">Sem registros para apresentar</div>
        </div>
    @endif
@stop
