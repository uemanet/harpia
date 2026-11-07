@extends('layouts.modulos.default')

@section('title')
    Fontes Pagadoras
@stop

@section('subtitle')
    Módulo RH
@stop

@section('actionButton')
    {!!ActionButton::render($actionButton)!!}
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
                <form method="GET" class="d-flex w-100" action="{{{ route('rh.fontespagadoras.index') }}}">
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="fpg_razao_social" id="fpg_razao_social" value="{{Request::input('fpg_razao_social')}}" placeholder="Razão social">
                    </div>
                    <div class="col-md-3">
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
