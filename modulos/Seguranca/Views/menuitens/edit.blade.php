@extends('layouts.modulos.default')

@section('title')
    Editar Item de Menu
@stop

@section('subtitle')
    {{$itemMenu->mit_nome}}
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title m-0">Formulário de Edição de Item de Menu</h3>
            </div>
            <form action="{{ route('seguranca.menuitens.edit', [$itemMenu->mit_id]) }}" method="POST" id="form" role="form">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        @include('Seguranca::menuitens.includes.formulario')
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary" style="float: right">Salvar Item</button>
                </div>
            </form>
        </div>
    </div>
@stop