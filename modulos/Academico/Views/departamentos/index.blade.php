@extends('layouts.modulos.default')

@section('title')
    Departamentos
@stop

@section('subtitle')
    Gerenciamento de departamentos
@stop

@section('actionButton')
    {!!ActionButton::render($actionButton)!!}
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
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
                    <form method="GET" action="{{{ route('academico.departamentos.index') }}}" class="d-flex w-100">
                        <div class="col-md-9 px-1">
                            <input type="text" class="form-control" name="dep_nome" id="dep_nome" value="{{Request::input('dep_nome')}}" placeholder="Nome do departamento">
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
