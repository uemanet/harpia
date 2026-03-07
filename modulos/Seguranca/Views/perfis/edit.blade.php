@extends('layouts.modulos.seguranca')

@section('title')
    Perfis
@stop

@section('subtitle')
    Alterar perfil :: {{$perfil->prf_nome}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de perfil</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('seguranca.perfis.edit', [$perfil->prf_id]) }}" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $perfil - inputs devem usar old('campo', $perfil->campo) --}}
                @include('Seguranca::perfis.includes.formulario_edit')
            </form>
        </div>
    </div>
@stop