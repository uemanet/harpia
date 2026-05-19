@extends('layouts.modulos.default')

@section('title')
    Setores
@stop

@section('subtitle')
    Módulo RH
@stop

@section('actionButton')
    {!!ActionButton::render($actionButton)!!}
@stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title m-0"><i class="fa fa-filter"></i> Filtrar dados</h3>

            <div class="card-tools float-end">
                <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{{ route('rh.setores.index') }}}">
                <div class="row">
                    <div class="col-md-9 px-1">
                        <input type="text" class="form-control" name="set_descricao" id="set_id" value="{{Request::input('set_descricao')}}" placeholder="Descrição do setor">
                    </div>
                    <div class="col-md-3 px-1">
                        <button type="submit" class="btn btn-primary w-100">Buscar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @if(!is_null($tabela))
        <div class="card card-primary card-outline my-2">
            <div class="card-header">
                {!! $tabela->render() !!}
            </div>
        </div>

        <div class="text-center">{!! $paginacao->links('pagination::bootstrap-4') !!}</div>

    @else
        <div class="card card-primary card-outline">
            <div class="card-body">Sem registros para apresentar</div>
        </div>
    @endif
@stop
