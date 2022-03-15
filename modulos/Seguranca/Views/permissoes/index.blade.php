@extends('layouts.modulos.seguranca')

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
                <form method="GET" action="{{ route('seguranca.permissoes.index') }}">
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="prm_nome" id="prm_nome" value="{{Request::input('prm_nome')}}" placeholder="Nome da permissão">
                    </div>
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="prm_rota" id="prm_rota" value="{{Request::input('prm_rota')}}" placeholder="Nome da rota">
                    </div>
                    <div class="col-md-2">
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

        <div class="text-center">{!! $paginacao->links('pagination::bootstrap-4') !!}</div>

    @else
        <div class="box box-primary">
            <div class="box-body">Sem registros para apresentar</div>
        </div>
    @endif
@stop
