@extends('layouts.modulos.default')

@section('title', 'Informações do Professor')

@section('content')
    @include('Geral::pessoas.includes.dadospessoais')
    @include('Geral::pessoas.includes.documentos')
    @include('Geral::pessoas.includes.titulacoes')

@endsection