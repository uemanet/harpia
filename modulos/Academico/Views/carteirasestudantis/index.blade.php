@extends('layouts.modulos.academico')

@section('title')
    Carteiras de Estudante
@stop

@section('subtitle')
    Gerenciamento de Listas de Carteiras de Estudantes
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
                <form method="GET" action="{{ route('academico.carteirasestudantis.index') }}">
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="lst_nome" id="crs_nome" value="{{Request::input('lst_nome')}}" placeholder="Nome da Lista">
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

        <div class="text-center">{!! $paginacao->links('pagination::bootstrap-4') !!}</div>

    @else
        <div class="box box-primary">
            <div class="box-body">Sem registros para apresentar</div>
        </div>
    @endif
@stop
