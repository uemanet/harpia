@extends('layouts.modulos.default')

@section('title') Configuração de Registro @stop
@section('subtitle') Parâmetros Operacionais @stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title m-0">Parâmetros do módulo de ponto</h3>
        </div>
        <form method="POST" action="{{ route('rh.configuracoesponto.update') }}">
            {{ csrf_field() }}
            <div class="card-body">
                @foreach($configuracoes as $config)
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>{{ $config->cop_chave }}</label>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="configuracoes[{{ $config->cop_chave }}]" value="{{ $config->cop_valor }}">
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted">{{ $config->cop_descricao }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success">Salvar Configurações</button>
            </div>
        </form>
    </div>
@stop
