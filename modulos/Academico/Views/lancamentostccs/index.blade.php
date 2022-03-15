@extends('layouts.modulos.academico')

@section('title')
    Lançamento de TCC
@stop

@section('subtitle')
    Gerenciamento de TCC
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
            <div class="box-body">Sem turmas com disciplina de TCC cadastradas</div>
        </div>
    @endif
@stop
