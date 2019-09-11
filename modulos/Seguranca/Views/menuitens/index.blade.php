@extends('layouts.modulos.seguranca')

@section('title')
    Itens de Menu
@stop

@section('subtitle')
    Gerenciamento de itens
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
                <form method="GET" action="{{ route('seguranca.menuitens.index') }}">
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="mit_nome" id="mit_nome" value="{{Request::input('mit_nome')}}" placeholder="Nome">
                    </div>
                    <div class="col-md-3">
                        <select name="mit_mod_id" id="mit_mod_id" class="form-control">
                            <option value="">Selecione um módulo</option>
                            @foreach($modulos as $key => $value)
                                <option value="{{$key}}" @if($key == Request::input('mit_mod_id')) selected @endif>{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="submit" class="form-control btn-primary" value="Buscar">
                    </div>
                </form>
            </div>
        </div>
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