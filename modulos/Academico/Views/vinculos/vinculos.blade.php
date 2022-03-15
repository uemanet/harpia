@extends('layouts.modulos.seguranca')

@section('title')
    Vínculos
@stop

@section('subtitle')
    Módulo Acadêmico :: Usuário : <b>{{$usuario->usr_usuario}}</b>
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
        <div class="text-center">{!! $paginacao->links('pagination::bootstrap-4') !!}</div>
    @else
        <div class="box box-primary">
            <div class="box-body">Sem registros para apresentar</div>
        </div>
    @endif
@stop
