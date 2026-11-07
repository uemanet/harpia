@extends('layouts.modulos.default')

@section('title')
    Colaboradores
@stop

@section('details')
    Gerenciamento de Horas Trabalhadas de Colaborador : <b>{{$colaborador->pessoa->pes_nome}}</b>
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
        <div class="box box-primary">
            <div class="card-body">Sem registros para apresentar</div>
        <div class="card card-primary card-outline">
            <div class="card-body">Sem registros para apresentar</div>
        </div>
    @endif
@stop

