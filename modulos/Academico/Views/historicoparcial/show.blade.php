@extends('layouts.modulos.default')

@section('title', 'Informações do Aluno')

@section('content')
    <section class="row py-2">
        @include('Geral::pessoas.includes.dadospessoais')
    </section>
    <section class="row py-2">
        @include('Academico::historicoparcial.includes.matriculas')
    </section>
@endsection