@extends('layouts.modulos.default')

@section('title')
    Permissoes
@stop

@section('subtitle')
    Módulo de Segurança
@stop

@section('actionButton')
    {!!ActionButton::render($actionButton)!!}
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title m-0"><i class="fa fa-filter"></i> Filtrar dados</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <form method="GET" action="{{ route('seguranca.permissoes.index') }}" class="w-100 d-flex">
                        <div class="col-md-5 px-1">
                            <input type="text" class="form-control" name="prm_nome" id="prm_nome" value="{{Request::input('prm_nome')}}" placeholder="Nome da permissão">
                        </div>
                        <div class="col-md-5 px-1">
                            <input type="text" class="form-control" name="prm_rota" id="prm_rota" value="{{Request::input('prm_rota')}}" placeholder="Nome da rota">
                        </div>
                        <div class="col-md-2 px-1">
                            <input type="submit" class="btn btn-primary w-100" value="Buscar">
                        </div>
                    </form>
                </div>
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
                    Sem registros para apresentar
                </div>
            @endif
        </div>
    </div>
@stop
