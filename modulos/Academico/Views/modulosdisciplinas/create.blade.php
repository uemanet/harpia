@extends('layouts.modulos.academico')

@section('title')
    Vínculo de Disciplinas ao XXXXXXXXX
@stop

@section('subtitle')
    Módulo Acadêmico
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-search"></i> Buscar Disciplinas</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <form method="GET" action="{{ url('/academico/modulosdisciplinas/create/'.$idModulo) }}">
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="dis_nome" id="dis_nome" value="{{Input::get('dis_nome')}}" placeholder="Nome da disciplina">
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
