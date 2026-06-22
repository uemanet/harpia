@extends('layouts.modulos.default')

@section('title', 'Informações da Fonte Pagadora')

@section('content')
    @include('RH::fontespagadoras.includes.dadosfontepagadora')
    @include('RH::fontespagadoras.includes.vinculosfontespagadoras')
@endsection