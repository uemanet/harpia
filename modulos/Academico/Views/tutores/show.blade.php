@extends('layouts.modulos.academico')

@section('title', 'Informações do Tutor')

@section('content')
    @include('Geral::pessoas.includes.dadospessoais')
    @include('Geral::pessoas.includes.documentos')
    @include('Geral::pessoas.includes.instituicao')
    @include('Geral::pessoas.includes.titulacoes')

@endsection