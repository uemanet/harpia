@extends('layouts.modulos.default')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection

@section('title')
    Usuarios do Dispositivo
@stop

@section('subtitle')
    Gestao Control iD
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-filter"></i> Selecionar dispositivo</h3>
        </div>
        <div class="box-body">
            <form method="GET" action="{{ route('rh.dispositivousuarios.index') }}">
                <div class="row">
                    <div class="col-md-10">
                        <select name="dis_id" class="form-control">
                            <option value="">Selecione um dispositivo</option>
                            @foreach($dispositivos as $dispositivoId => $dispositivoNome)
                                <option value="{{ $dispositivoId }}" {{ (string) request('dis_id') === (string) $dispositivoId ? 'selected' : '' }}>
                                    {{ $dispositivoNome }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-block">Carregar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($dispositivo)
        <div class="row">
            <div class="col-md-4">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>{{ $resumo['total'] ?? 0 }}</h3>
                        <p>Usuarios encontrados</p>
                    </div>
                    <div class="icon"><i class="fa fa-users"></i></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{ $resumo['vinculados'] ?? 0 }}</h3>
                        <p>Vinculados ao Harpia</p>
                    </div>
                    <div class="icon"><i class="fa fa-link"></i></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{ $resumo['sem_foto'] ?? 0 }}</h3>
                        <p>Sem foto facial</p>
                    </div>
                    <div class="icon"><i class="fa fa-camera"></i></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Cadastrar no dispositivo</h3>
                    </div>
                    <form method="POST" action="{{ route('rh.dispositivousuarios.create') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="dis_id" value="{{ $dispositivo->dis_id }}">

                        <div class="box-body">
                            <div class="form-group @if($errors->has('col_id')) has-error @endif">
                                <label>Colaborador</label>
                                <select name="col_id" class="form-control">
                                    <option value="">Selecione</option>
                                    @foreach($colaboradores as $colaboradorId => $colaboradorNome)
                                        <option value="{{ $colaboradorId }}" {{ old('col_id') == $colaboradorId ? 'selected' : '' }}>
                                            {{ $colaboradorNome }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('col_id')) <p class="help-block">{{ $errors->first('col_id') }}</p> @endif
                            </div>

                            <div class="form-group @if($errors->has('nome')) has-error @endif">
                                <label>Nome no dispositivo</label>
                                <input type="text" name="nome" class="form-control" value="{{ old('nome') }}" placeholder="Se vazio, usa o nome do colaborador">
                                @if ($errors->has('nome')) <p class="help-block">{{ $errors->first('nome') }}</p> @endif
                            </div>

                            <div class="form-group @if($errors->has('registration')) has-error @endif">
                                <label>Registration</label>
                                <input type="text" name="registration" class="form-control" value="{{ old('registration') }}" placeholder="Se vazio, usa o col_id">
                                @if ($errors->has('registration')) <p class="help-block">{{ $errors->first('registration') }}</p> @endif
                            </div>

                            <div class="form-group @if($errors->has('foto')) has-error @endif">
                                <label>Foto facial</label>
                                <input type="file" name="foto" class="form-control">
                                <p class="help-block">Opcional. Se enviada, a foto sera cadastrada diretamente no iDFace.</p>
                                @if ($errors->has('foto')) <p class="help-block">{{ $errors->first('foto') }}</p> @endif
                            </div>

                            <div class="form-group @if($errors->has('dispositivos_destino')) has-error @endif">
                                <label>Cadastrar tambem em outros aparelhos</label>
                                <select name="dispositivos_destino[]" class="form-control" multiple>
                                    @php($dispositivosDestino = old('dispositivos_destino', [$dispositivo->dis_id]))
                                    @foreach($dispositivos as $dispositivoId => $dispositivoNome)
                                        <option value="{{ $dispositivoId }}" {{ in_array((string) $dispositivoId, array_map('strval', (array) $dispositivosDestino), true) ? 'selected' : '' }}>
                                            {{ $dispositivoNome }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="help-block">Se nada for alterado, o cadastro ocorre no aparelho atualmente carregado.</p>
                                @if ($errors->has('dispositivos_destino')) <p class="help-block">{{ $errors->first('dispositivos_destino') }}</p> @endif
                            </div>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="cadastrar_em_todos_dispositivos" value="1" {{ old('cadastrar_em_todos_dispositivos') ? 'checked' : '' }}>
                                    Cadastrar em todos os aparelhos ativos
                                </label>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fa fa-plus"></i> Cadastrar usuario
                            </button>
                        </div>
                    </form>
                </div>

                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Acoes</h3>
                    </div>
                    <div class="box-body">
                        <form method="POST" action="{{ route('rh.dispositivousuarios.sincronizar') }}" style="margin-bottom: 10px;">
                            {{ csrf_field() }}
                            <input type="hidden" name="dis_id" value="{{ $dispositivo->dis_id }}">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fa fa-refresh"></i> Sincronizar do dispositivo
                            </button>
                        </form>

                        <a href="{{ route('rh.dispositivousuarios.exportarcsv', ['id' => $dispositivo->dis_id]) }}" class="btn btn-default btn-block">
                            <i class="fa fa-download"></i> Exportar CSV Control iD
                        </a>

                        <hr>

                        <form method="POST" action="{{ route('rh.dispositivousuarios.sincronizartodos') }}" style="margin-bottom: 10px;">
                            {{ csrf_field() }}
                            <input type="hidden" name="dis_id" value="{{ $dispositivo->dis_id }}">

                            <div class="form-group">
                                <label>Sincronizar usuarios ativos do Harpia em</label>
                                <select name="dispositivos_destino[]" class="form-control" multiple>
                                    @php($destinosSincronizacao = old('dispositivos_destino', [$dispositivo->dis_id]))
                                    @foreach($dispositivos as $dispositivoId => $dispositivoNome)
                                        <option value="{{ $dispositivoId }}" {{ in_array((string) $dispositivoId, array_map('strval', (array) $destinosSincronizacao), true) ? 'selected' : '' }}>
                                            {{ $dispositivoNome }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="help-block">A acao replica os colaboradores ativos do Harpia nos aparelhos selecionados.</p>
                            </div>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="sincronizar_todos_dispositivos" value="1" {{ old('sincronizar_todos_dispositivos') ? 'checked' : '' }}>
                                    Usar todos os aparelhos ativos
                                </label>
                            </div>

                            <button type="submit" class="btn btn-success btn-block" onclick="return confirm('Tem certeza que deseja sincronizar os usuarios ativos do Harpia para os aparelhos selecionados?')">
                                <i class="fa fa-exchange"></i> Sincronizar usuarios entre aparelhos
                            </button>
                        </form>

                        <a href="{{ route('rh.vincularcolaboradores.index', ['dis_id' => $dispositivo->dis_id]) }}" class="btn btn-warning btn-block">
                            <i class="fa fa-link"></i> Abrir tela de vinculacao
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Usuarios lidos do dispositivo</h3>
                    </div>
                    <div class="box-body table-responsive">
                        @if(count($usuarios))
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>ID iDFace</th>
                                    <th>Registration</th>
                                    <th>Nome</th>
                                    <th>Foto</th>
                                    <th>Vinculacao</th>
                                    <th style="width: 210px;">Acoes</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($usuarios as $usuario)
                                    <tr>
                                        <td>{{ $usuario['user_id'] }}</td>
                                        <td>{{ $usuario['registration'] ?: '-' }}</td>
                                        <td>{{ $usuario['nome'] ?: '-' }}</td>
                                        <td>
                                            @if($usuario['foto_ok'])
                                                <span class="label label-success">Foto OK</span>
                                            @else
                                                <span class="label label-warning">Sem foto</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($usuario['col_id'])
                                                <span class="label label-success">Vinculado</span>
                                                <div>{{ $usuario['colaborador_nome'] }}</div>
                                            @else
                                                <span class="label label-warning">Pendente</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('rh.dispositivousuarios.edit', ['id' => $usuario['user_id'], 'dis_id' => $dispositivo->dis_id]) }}" class="btn btn-xs btn-primary">
                                                <i class="fa fa-pencil"></i> Editar
                                            </a>

                                            <form method="POST" action="{{ route('rh.dispositivousuarios.delete') }}" style="display: inline;">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="dis_id" value="{{ $dispositivo->dis_id }}">
                                                <input type="hidden" name="user_id" value="{{ $usuario['user_id'] }}">
                                                <input type="hidden" name="todos_dispositivos" value="0">
                                                <button type="submit" class="btn btn-xs btn-danger"
                                                        data-registration="{{ $usuario['registration'] }}"
                                                        onclick="return confirmarExclusao(this)">
                                                    <i class="fa fa-trash"></i> Excluir
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-info" style="margin-bottom: 0;">
                                Nenhum usuario retornado pelo dispositivo selecionado.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
@stop

@section('scripts')
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('select').select2();
        });

        function confirmarExclusao(botao) {
            var form = botao.closest('form');
            var registration = botao.getAttribute('data-registration');

            if (!confirm('Tem certeza que deseja remover este usuario do dispositivo?')) {
                return false;
            }

            if (registration && registration !== '') {
                if (confirm('Este usuario possui registration "' + registration + '".\n\nDeseja remove-lo de TODOS os dispositivos?\n\nOK = Excluir de todos\nCancelar = Excluir apenas deste')) {
                    form.querySelector('input[name="todos_dispositivos"]').value = '1';
                }
            }

            return true;
        }
    </script>
@endsection
