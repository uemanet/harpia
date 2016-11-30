@extends('layouts.modulos.geral')

@section('title', 'Informações da Pessoa')

@section('content')
    @include('Geral::pessoas.includes.dadospessoais')
    @include('Geral::pessoas.includes.documentos')
    @include('Geral::pessoas.includes.titulacoes')
@endsection