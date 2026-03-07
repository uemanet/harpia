@extends('layouts.modulos.academico')

@section('title')
    Professores
@stop

@section('subtitle')
    Alterar professor :: {{$pessoa->pes_nome}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de Edição de Professor</h3>
        </div>
        <div class="box-body">
            <form action="{{ route('academico.professores.edit', [$pessoa->pes_id]) }}" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $pessoa - inputs devem usar old('campo', $pessoa->campo) --}}

            <h4 class="box-title">
                Dados de Pessoa
            </h4>
            @include('Academico::professores.includes.formulario', ['pessoa' => $pessoa])

            <div class="row">
                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-primary pull-right">Salvar dados</button>
                </div>
            </div>
            </form>
        </div>
    </div>
@stop
