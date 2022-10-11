@extends('layouts.modulos.rh')

@section('title')
    Justificativas
@stop

@section('subtitle')
    Gerenciamento de Justificativas :: {{$horaTrabalhada->colaborador->pessoa->pes_nome}}
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
