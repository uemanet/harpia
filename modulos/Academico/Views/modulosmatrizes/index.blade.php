@extends('layouts.modulos.academico')

@section('title')
    Módulos
@stop

@section('subtitle')
    Gerenciamento de matrizes curriculares :: {{$curso->crs_nome}} :: {{$matrizcurricular->mtc_titulo}}
@stop

@section('actionButton')
    {!!ActionButton::render($actionButton)!!}
@stop

@section('content')
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
