@extends('layouts.modulos.academico')

@section('title', 'Informações do Colaborador')

@section('content')
    <div style="display: flex;">
        @if (Breadcrumbs::exists())
            <ol class="breadcrumb" style="float: left; background-color: #ecf0f5">
                {{ Breadcrumbs::render() }}
            </ol>
        @endif
    </div>
    @include('Geral::pessoas.includes.dadospessoais')
    @include('RH::colaboradores.includes.dadoscolaborador')
    @include('Geral::pessoas.includes.documentos')
    @include('Geral::pessoas.includes.titulacoes')
    @include('RH::colaboradores.includes.atividadesextras')
    @include('RH::colaboradores.includes.contascolaboradores')
    @include('RH::colaboradores.includes.salarioscolaboradores')
    @include('RH::colaboradores.includes.periodosaquisitivos')
@endsection