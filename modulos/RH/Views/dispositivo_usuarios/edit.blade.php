@extends('layouts.modulos.default')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection

@section('title')
    Editar Usuário do Dispositivo
@stop

@section('subtitle')
    Gestão Control iD
@stop

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title m-0">Resumo do usuário</h3>
                </div>
                <div class="card-body">
                    <p><strong>Dispositivo:</strong> {{ $dispositivo->dis_nome }}</p>
                    <p><strong>ID iDFace:</strong> {{ $usuario['user_id'] }}</p>
                    <p><strong>Registration atual:</strong> {{ $usuario['registration'] ?: '-' }}</p>
                    <p><strong>Nome atual:</strong> {{ $usuario['nome'] ?: '-' }}</p>
                    <p>
                        <strong>Vinculação:</strong>
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
                <div class="card-footer">
                    <a href="{{ route('rh.dispositivousuarios.index', ['dis_id' => $dispositivo->dis_id]) }}" class="btn btn-secondary w-100">
                        <i class="fa fa-arrow-left"></i> Voltar para a listagem
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title m-0">Atualizar vínculo do usuário</h3>
                </div>
                <form method="POST" action="{{ route('rh.dispositivousuarios.edit', ['id' => $usuario['user_id']]) }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <input type="hidden" name="dis_id" value="{{ $dispositivo->dis_id }}">

                    <div class="card-body">
                        <div class="form-group @if($errors->has('col_id')) has-error @endif">
                            <label>Colaborador vinculado</label>
                            <select name="col_id" class="form-control">
                                <option value="">Selecione</option>
                                @foreach($colaboradores as $colaboradorId => $colaboradorNome)
                                    <option value="{{ $colaboradorId }}" {{ (string) old('col_id', $usuario['col_id']) === (string) $colaboradorId ? 'selected' : '' }}>
                                        {{ $colaboradorNome }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('col_id')) <p class="help-block">{{ $errors->first('col_id') }}</p> @endif
                        </div>

                        <p class="text-muted">
                            Nome, registration e foto facial serao recalculados automaticamente com base no colaborador selecionado.
                        </p>

                        <div class="form-group @if($errors->has('foto')) has-error @endif">
                            <label>Nova foto facial no dispositivo</label>
                            <input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
                            <p class="help-block">Opcional. Se enviada, atualiza a foto facial do colaborador e reaplica essa foto neste dispositivo.</p>
                            @if ($errors->has('foto')) <p class="help-block">{{ $errors->first('foto') }}</p> @endif
                        </div>

                        @if($usuario['registration'])
                        <div class="form-check">
                            <input type="hidden" name="aplicar_todos" value="0">
                            <input type="checkbox" class="form-check-input" name="aplicar_todos" value="1" {{ old('aplicar_todos', '1') === '1' ? 'checked' : '' }}>
                            <label class="form-check-label">Aplicar também nos outros dispositivos onde o usuário existe (registration: {{ $usuario['registration'] }})</label>
                        </div>
                        @endif
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Atualizar no dispositivo
                        </button>
                    </div>
                </form>
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
