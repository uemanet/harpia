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
        <div class="card card-primary card-outline p-0">
            <div class="card-header with-border">
                <h3 class="card-title">Formulário de edição de grupo</h3>
            </div>
            <div class="card-body">
                <form action="{{  url('/') . "/academico/grupos/edit/" . $grupo->grp_id }}" method="POST" id="form" role="form">
                    @csrf
                    @method('PUT')
                    @include('Academico::grupos.includes.formulario_edit')
                </form>
            </div>
        </div>
    </div>
@stop
