@extends('layouts.modulos.default')

@section('title', 'Informações do Colaborador')

@section('content')
    <div style="display: flex;">
        @if (Breadcrumbs::exists())
            <ol class="breadcrumb" style="float: left;">
                {{ Breadcrumbs::render() }}
            </ol>
        @endif
    </div>

    <section class="my-2">
        @include('Geral::pessoas.includes.dadospessoais')
    </section>
    <section class="my-2">
        @include('RH::colaboradores.includes.dadoscolaborador')
    </section>
    <section class="my-2">
        @include('Geral::pessoas.includes.documentos')
    </section>
    <section class="my-2">
        @include('Geral::pessoas.includes.titulacoes')
    </section>
    <section class="my-2">
        @include('RH::colaboradores.includes.atividadesextras')
    </section>
    <section class="my-2">
        @include('RH::colaboradores.includes.contascolaboradores')
    </section>
    <section class="my-2">
        @include('RH::colaboradores.includes.salarioscolaboradores')
    </section>
    <section class="my-2">
        @include('RH::colaboradores.includes.periodosaquisitivos')
    </section>
@endsection