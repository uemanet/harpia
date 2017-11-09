@extends('layouts.modulos.academico')

@section('title')
    Histórico de Movimentação
@stop

@section('subtitle')
    Grupo :: {{ $grupo->grp_nome }}
@stop

@section('content')
    {{--@if(!is_null($tabela))--}}
        {{--<div class="box box-primary">--}}
            {{--<div class="box-header">--}}
                {{--{!! $tabela->render() !!}--}}
            {{--</div>--}}
        {{--</div>--}}

        {{--<div class="text-center">{!! $paginacao->links() !!}</div>--}}
    {{--@else--}}
        {{--<div class="box box-primary">--}}
            {{--<div class="box-body">Sem registros para apresentar</div>--}}
        {{--</div>--}}
    {{--@endif--}}
@stop
