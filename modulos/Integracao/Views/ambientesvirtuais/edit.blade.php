@extends('layouts.modulos.integracao')

@section('title')
    Ambientes Virtuais
@stop

@section('subtitle')
    Edição de ambiente virtual
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de ambientes virtuais</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('integracao.ambientesvirtuais.edit', [$ambientevirtual->amb_id]) }}" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $ambientevirtual - inputs devem usar old('campo', $ambientevirtual->campo) --}}
                @include('Integracao::ambientesvirtuais.includes.formulario')
            </form>

        </div>
    </div>
@stop
