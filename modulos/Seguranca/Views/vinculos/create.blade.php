@extends('layouts.modulos.seguranca')

@section('title')
    Vínculos
@stop

@section('subtitle')
    Módulo de Segurança :: {{ $usuario->pessoa->pes_nome }}
@stop

@section('actionButton')
    {!!ActionButton::render($actionButtons)!!}
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
    @endif
@stop
