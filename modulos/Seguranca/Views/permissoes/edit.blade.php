@extends('layouts.modulos.seguranca')

@section('title')
    Editar Permissão
@stop

@section('subtitle')
    {{$permissao->prm_nome}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Editar Permissão</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('seguranca.permissoes.edit', [$permissao->prm_id]) }}" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $permissao - inputs devem usar old('campo', $permissao->campo) --}}
                @include('Seguranca::permissoes.includes.formulario')
            </form>
        </div>
    </div>
@stop