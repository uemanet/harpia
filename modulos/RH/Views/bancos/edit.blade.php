@extends('layouts.modulos.default')

@section('title')
    Bancos
@stop

@section('subtitle')
    Edição de banco
@stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title m-0">Formulário de edição de banco</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('rh.bancos.edit', [$banco->ban_id]) }}" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $banco - inputs devem usar old('campo', $banco->campo) --}}
                @include('RH::bancos.includes.formulario')
            </form>

        </div>
    </div>
@stop
