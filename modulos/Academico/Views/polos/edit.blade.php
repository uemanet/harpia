@extends('layouts.modulos.academico')

@section('title')
    Polos
@stop

@section('subtitle')
    Edição de polo
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de polos</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('academico.polos.edit', [$polo->pol_id]) }}" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $polo - inputs devem usar old('campo', $polo->campo) --}}
                @include('Academico::polos.includes.formulario')
            </form>

        </div>
    </div>
@stop
