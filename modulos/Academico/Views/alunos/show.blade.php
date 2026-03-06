@extends('layouts.modulos.default')

@section('title', 'Informações do Aluno')

@section('content')
    @include('Geral::pessoas.includes.dadospessoais')
    @include('Geral::pessoas.includes.documentos')
    @include('Geral::pessoas.includes.titulacoes')
    @include('Academico::alunos.includes.matriculas')
@endsection