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
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de grupo</h3>
        </div>
        <div class="box-body">
            <form action="url(" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $grupo - inputs devem usar old('campo', $grupo->campo) --}}
            @include('Academico::grupos.includes.formulario_edit')
            </form>
        </div>
    </div>
@stop
