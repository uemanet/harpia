@extends('layouts.modulos.default')

@section('title')
    Controle de Registros
@stop

@section('subtitle')
    Gerenciamento de registros
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
            <form action="#" method="GET" id="form" role="form">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="text" name="pes_nome" class="form-control" id="pes_nome" value="Request" placeholder="Nome" >
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="pes_email" class="form-control" id="pes_email" value="Request" placeholder="Email" >
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Buscar</button>
                        </div>
                    </div>
                </div>
            </form>
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
