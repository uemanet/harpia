@extends('layouts.modulos.default')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection

@section('title')
    Editar Usuario do Dispositivo
@stop

@section('subtitle')
    Gestao Control iD
@stop

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Resumo do usuario</h3>
                </div>
                <div class="box-body">
                    <p><strong>Dispositivo:</strong> {{ $dispositivo->dis_nome }}</p>
                    <p><strong>ID iDFace:</strong> {{ $usuario['user_id'] }}</p>
                    <p><strong>Registration atual:</strong> {{ $usuario['registration'] ?: '-' }}</p>
                    <p><strong>Nome atual:</strong> {{ $usuario['nome'] ?: '-' }}</p>
                    <p>
                        <strong>Vinculacao:</strong>
                        @if($usuario['col_id'])
                            {{ $usuario['colaborador_nome'] }}
                        @else
                            Pendente
                        @endif
                    </p>
                    <p>
                        <strong>Foto facial:</strong>
                        @if($usuario['foto_ok'])
                            Foto OK
                        @else
                            Sem foto
                        @endif
                    </p>
                </div>
                <div class="box-footer">
                    <a href="{{ route('rh.dispositivousuarios.index', ['dis_id' => $dispositivo->dis_id]) }}" class="btn btn-default btn-block">
                        <i class="fa fa-arrow-left"></i> Voltar para a listagem
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Atualizar dados do usuario</h3>
                </div>
                <form method="POST" action="{{ route('rh.dispositivousuarios.edit', ['id' => $usuario['user_id']]) }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <input type="hidden" name="dis_id" value="{{ $dispositivo->dis_id }}">

                    <div class="box-body">
                        <div class="form-group @if($errors->has('col_id')) has-error @endif">
                            <label>Colaborador vinculado</label>
                            <select name="col_id" class="form-control">
                                <option value="">Manter sem vinculo</option>
                                @foreach($colaboradores as $colaboradorId => $colaboradorNome)
                                    <option value="{{ $colaboradorId }}" {{ (string) old('col_id', $usuario['col_id']) === (string) $colaboradorId ? 'selected' : '' }}>
                                        {{ $colaboradorNome }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('col_id')) <p class="help-block">{{ $errors->first('col_id') }}</p> @endif
                        </div>

                        <div class="form-group @if($errors->has('nome')) has-error @endif">
                            <label>Nome no dispositivo</label>
                            <input type="text" name="nome" class="form-control" value="{{ old('nome', $usuario['nome']) }}">
                            @if ($errors->has('nome')) <p class="help-block">{{ $errors->first('nome') }}</p> @endif
                        </div>

                        <div class="form-group @if($errors->has('registration')) has-error @endif">
                            <label>Registration</label>
                            <input type="text" name="registration" class="form-control" value="{{ old('registration', $usuario['registration']) }}">
                            @if ($errors->has('registration')) <p class="help-block">{{ $errors->first('registration') }}</p> @endif
                        </div>

                        <div class="form-group @if($errors->has('foto')) has-error @endif">
                            <label>Nova foto facial</label>
                            <input type="file" name="foto" class="form-control">
                            <p class="help-block">Se enviada, a foto sera atualizada no iDFace durante o salvamento.</p>
                            @if ($errors->has('foto')) <p class="help-block">{{ $errors->first('foto') }}</p> @endif
                        </div>

                        @if($usuario['registration'])
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="aplicar_todos" value="1" {{ old('aplicar_todos') ? 'checked' : '' }}>
                                Aplicar tambem nos outros dispositivos onde o usuario existe (registration: {{ $usuario['registration'] }})
                            </label>
                        </div>
                        @endif
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Salvar alteracoes
                        </button>
                    </div>
                </form>
            </div>

            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Acoes de foto facial</h3>
                </div>
                <div class="box-body">
                    <form method="POST" action="{{ route('rh.dispositivousuarios.atualizarfoto', ['id' => $usuario['user_id']]) }}" enctype="multipart/form-data" style="margin-bottom: 10px;">
                        {{ csrf_field() }}
                        <input type="hidden" name="dis_id" value="{{ $dispositivo->dis_id }}">

                        <div class="row">
                            <div class="col-md-9">
                                <input type="file" name="foto" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-success btn-block">
                                    <i class="fa fa-camera"></i> Enviar foto
                                </button>
                            </div>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('rh.dispositivousuarios.removerfoto', ['id' => $usuario['user_id']]) }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="dis_id" value="{{ $dispositivo->dis_id }}">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja remover a foto facial deste usuario?')">
                            <i class="fa fa-trash"></i> Remover foto facial
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('select').select2();
        });
    </script>
@endsection
