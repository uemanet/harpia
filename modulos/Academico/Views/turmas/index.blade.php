@extends('layouts.modulos.default')

@section('title')
    Turmas
@stop

@section('subtitle')
    Gerenciamento de turmas :: {{$ofertacurso->curso->crs_nome}} ::Oferta do ano de {{$ofertacurso->ofc_ano}}
@stop

@section('actionButton')
    {!!ActionButton::render($actionButton)!!}
@stop

@section('content')

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
