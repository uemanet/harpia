@extends('layouts.modulos.academico')

@section('title')
    Controle de Registros
@stop

@section('subtitle')
    Gerenciamento de registros
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
                {!! Form::open(["url" => url('/') . "/academico/controlederegistro/index", "method" => "GET", "id" => "form", "role" => "form"]) !!}
                    <div class="col-md-5">
                        {{ Form::text('pes_nome', null, ['class' => 'form-control', 'id' => 'pes_nome', 'value' => Input::get('pes_nome'), 'placeholder' => 'Nome']) }}
                    </div>
                    <div class="col-md-5">
                        {{ Form::text('pes_email', null, ['class' => 'form-control', 'id' => 'pes_email', 'value' => Input::get('pes_email'), 'placeholder' => 'Email']) }}
                    </div>
                    <div class="col-md-2">
                        {{ Form::submit('Buscar', ['class' => 'form-control btn-primary']) }}
                    </div>
                {!! Form::close() !!}
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