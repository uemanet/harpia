@extends('layouts.modulos.default')

@section('title')
    Grupos
@stop

@section('subtitle')
    Gerenciamento de grupos :: {{$oferta->curso->crs_nome}} ::Oferta do ano {{$oferta->ofc_ano}} :: {{$turma->trm_nome}}
@stop

@section('actionButton')
    {!!ActionButton::render($actionButton)!!}
@stop

@section('content')
    @if(!is_null($tabela))
        <div class="card card-primary card-outline p-0">
            <div class="card-header">
                {!! $tabela->render() !!}
            </div>
        </div>

        <div class="text-center">{!! $paginacao->links('pagination::bootstrap-4') !!}</div>
    @else
        <div class="card card-primary card-outline p-0">
            <div class="card-body">Sem registros para apresentar</div>
        </div>
    @endif
@stop
