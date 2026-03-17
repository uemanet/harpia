@extends('layouts.modulos.default')

@section('title')
    Tutores
@stop

@section('subtitle')
    Alterar tutor :: {{$pessoa->pes_nome}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de Edição de Tutor</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('academico.tutores.edit', [$pessoa->pes_id]) }}" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $pessoa - inputs devem usar old('campo', $pessoa->campo) --}}

            <h4 class="box-title">
                Dados de Pessoa
            </h4>
            @include('Geral::pessoas.includes.formulario', ['pessoa' => $pessoa])

            <div class="row">
                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-primary pull-right">Salvar dados</button>
                </div>
            </div>
            </form>
        </div>
    </div>
@stop