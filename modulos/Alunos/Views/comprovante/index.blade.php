@extends('layouts.modulos.academico')

@section('title')
    Histórico Parcial
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
                <form method="GET" action="{{ route('academico.historicoparcial.index') }}">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="pes_cpf" id="pes_cpf" value="{{Request::input('pes_cpf')}}" placeholder="CPF">
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="pes_nome" id="pes_nome" value="{{Request::input('pes_nome')}}" placeholder="Nome">
                    </div>
                    <div class="col-md-3">
                        <input type="email" class="form-control" name="pes_email" id="pes_email" value="{{Request::input('pes_email')}}" placeholder="Email">
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