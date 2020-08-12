@extends('layouts.modulos.academico')

@section('title', 'Informações do Colaborador')

@section('content')
    @include('Geral::pessoas.includes.dadospessoais')
    @include('RH::colaboradores.includes.dadoscolaborador')
    @include('Geral::pessoas.includes.documentos')
    @include('Geral::pessoas.includes.titulacoes')
    @include('RH::colaboradores.includes.atividadesextras')
    @include('RH::colaboradores.includes.contascolaboradores')

@endsection