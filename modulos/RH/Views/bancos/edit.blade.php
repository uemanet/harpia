@extends('layouts.modulos.rh')

@section('title')
    Bancos
@stop

@section('subtitle')
    Edição de banco
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de banco</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('rh.bancos.edit', [$banco->ban_id]) }}" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $banco - inputs devem usar old('campo', $banco->campo) --}}
                @include('RH::bancos.includes.formulario')
            </form>

        </div>
    </div>
@stop
