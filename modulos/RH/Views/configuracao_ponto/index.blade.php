@extends('layouts.modulos.default')

@section('title') Configuracoes de Ponto @stop
@section('subtitle') Parametros Operacionais @stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Parametros do modulo de ponto</h3>
        </div>
        <form method="POST" action="{{ route('rh.configuracoesponto.update') }}">
            {{ csrf_field() }}
            <div class="box-body">
                @foreach($configuracoes as $config)
                    <div class="row" style="margin-bottom: 15px;">
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
            <div class="box-footer">
                <button type="submit" class="btn btn-success">Salvar Configuracoes</button>
            </div>
        </form>
    </div>
@stop
