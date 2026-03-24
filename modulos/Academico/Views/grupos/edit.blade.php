@extends('layouts.modulos.default')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection

@section('title')
    Edição de Grupo
@stop

@section('subtitle')
    Alterar grupo :: {{$grupo->grp_nome}}
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de edição de grupo</h3>
        </div>
        <div class="card-body">
            <form action="url(" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $grupo - inputs devem usar old('campo', $grupo->campo) --}}
            @include('Academico::grupos.includes.formulario_edit')
            </form>
        </div>
    </div>
@stop
