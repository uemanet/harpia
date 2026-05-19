@extends('layouts.modulos.default')

@section('breadcrumbs')
    {{ Breadcrumbs::render('rh.horastrabalhadas.justificativas.index', $horaTrabalhada->htr_id) }}
@endsection

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
        <div class="card card-primary card-outline">
            <div class="card-header">
                {!! $tabela->render() !!}
            </div>
        </div>

        <div class="text-center">{!! $paginacao->links('pagination::bootstrap-4') !!}</div>

    @else
        <div class="card card-primary card-outline">
            <div class="card-body">Sem registros para apresentar</div>
        </div>
    @endif
@stop
