@extends('layouts.modulos.default')

@section('title')
    Vínculos
@stop

@section('subtitle')
    Edição de vínculo
@stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title m-0">Formulário de edição de vínculo</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('rh.vinculos.edit', [$vinculo->vin_id]) }}" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $vinculo - inputs devem usar old('campo', $vinculo->campo) --}}
                @include('RH::vinculos.includes.formulario')
            </form>

        </div>
    </div>
@stop
