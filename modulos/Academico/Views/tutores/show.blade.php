@extends('layouts.modulos.default')

@section('title', 'Informações do Tutor')

@section('content')
    <section class="py-2">
        @include('Geral::pessoas.includes.dadospessoais')
    </section>
    <section class="py-2">
        @include('Geral::pessoas.includes.documentos')
    </section>
    <section class="py-2">
        @include('Geral::pessoas.includes.titulacoes')
    </section>
@endsection