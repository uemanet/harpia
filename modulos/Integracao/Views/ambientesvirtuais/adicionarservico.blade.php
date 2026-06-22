@extends('layouts.modulos.default')

@section('title')
    Serviços do Ambiente Virtual
@stop

@section('subtitle')
    {{$ambiente->amb_nome}}
@stop

@section('content')
    <div class="row">

        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Adicionar Serviços ao Ambiente Virtual</h3>
            </div>

            <div class="card-body">
                <form class="d-flex w-100" action="{{ route('integracao.ambientesvirtuais.adicionarservico', [$ambiente->amb_id]) }}" method="POST" id="formAtribuirPerfil">
                    @csrf
                    <div class="form-group col-md-5 px-1">
                        <label for="asr_ser_id" class="form-label">Serviço*</label>
                        <div class="controls">
                            <select name="asr_ser_id" class="form-control" id="asr_ser_id">
                                <option value="">Selecione o serviço</option>
                                @foreach($servicos as $key => $value)
                                    <option value="{{ $key }}" {{ old('asr_ser_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('asr_ser_id')) <p class="help-block">{{ $errors->first('asr_ser_id') }}</p> @endif
                        </div>
                    </div>
                    <div class="form-group col-md-5 px-1">
                        <label for="asr_token" class="form-label">Token*</label>
                        <div class="controls">
                            <input type="text" name="asr_token" value="{{ old('asr_token') }}" id="asr_token" class="form-control select-control" placeholder="Digite o token" >
                            @if ($errors->has('asr_token')) <p class="help-block">{{ $errors->first('asr_token') }}</p> @endif
                        </div>
                    </div>
                    <div class="form-group col-md-2 px-1">
                        <label for="asr_token" class="form-label">&nbsp;</label>
                        <div class="controls">
                            <button type="submit" class="btn btn-primary w-100" id="btnAtribuir">Adicionar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row my-2">
        <div class="card card-primary card-outline">
        @if(count($ambiente->servicos))
            <div class="card-body">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <th style="width: 10px">#</th>
                        <th style="width: 10px">Serviço</th>
                        <th style="width: 20px">Slug</th>
                        <th style="width: 20px">Token</th>
                        <th style="width: 20px"></th>
                    </thead>
                    <tbody>
                        @foreach($ambiente->ambienteservico as $ambienteservico)
                            <tr>
                                <td>{{$ambienteservico->servico->ser_id}}</td>
                                <td>{{$ambienteservico->servico->ser_nome}}</td>
                                <td>{{$ambienteservico->servico->ser_slug}}</td>
                                <td>{{$ambienteservico->asr_token}}</td>
                                <td>
                                    {!! ActionButton::grid([
                                        'type' => 'LINE',
                                        'buttons' => [
                                            [
                                                'classButton' => 'btn btn-danger btn-delete',
                                                'icon' => 'fa fa-trash',
                                                'route' => 'integracao.ambientesvirtuais.deletarservico',
                                                'parameters' => ['id' => $ambiente->amb_id],
                                                'id' => $ambienteservico->asr_id,
                                                'label' => '',
                                                'method' => 'post'
                                            ]
                                        ]
                                    ]) !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="card-body text-center">
                <p>Sem serviços adicionados ao ambiente virtual</p>
            </div>
        @endif
        </div>
    </div>
@stop