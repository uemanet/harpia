@extends('layouts.modulos.academico')

@section('title')
    Vínculo de Mediadores
@stop

@section('subtitle')
    Oferta do ano de {{$oferta->ofc_ano}} :: {{$turma->trm_nome}} :: {{$grupo->grp_nome}}
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

        <div class="text-center">{!! $paginacao->links('pagination::bootstrap-4') !!}</div>

    @else
        <div class="box box-primary">
            <div class="box-body">Sem registros para apresentar</div>
        </div>
    @endif
@stop
