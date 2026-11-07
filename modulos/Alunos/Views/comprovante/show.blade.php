@extends('layouts.modulos.default')

@section('title', 'Informações do Aluno')

@section('content')
    @include('Geral::pessoas.includes.dadospessoais')

    @include('Academico::historicoparcial.includes.matriculas')

@endsection