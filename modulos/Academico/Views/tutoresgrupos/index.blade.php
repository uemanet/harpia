@extends('layouts.modulos.default')

@section('title')
    Vínculo de Tutores
@stop

@section('subtitle')
    Oferta do ano de {{$oferta->ofc_ano}} :: {{$turma->trm_nome}} :: {{$grupo->grp_nome}}
@stop

@section('actionButton')
    {!!ActionButton::render($actionButton)!!}
@stop

@section('content')

    <div class="row">
        <div class="card card-primary card-outline my-2 p-0">
            @if(!is_null($tabela))
                <div class="card-body p-0">
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
