@extends('layouts.modulos.academico')

@section('title', 'Informações do Aluno')

@section('content')
    @include('Geral::pessoas.includes.dadospessoais')

    @include('Academico::historicoparcial.includes.matriculas')

@endsection