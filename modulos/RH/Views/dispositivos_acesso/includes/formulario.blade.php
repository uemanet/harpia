<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('dis_nome')) has-error @endif">
        <label for="dis_nome" class="control-label">Nome*</label>
        <input type="text" name="dis_nome" class="form-control" value="{{ old('dis_nome', isset($dispositivo) ? $dispositivo->dis_nome : null) }}">
        @if ($errors->has('dis_nome')) <p class="help-block">{{ $errors->first('dis_nome') }}</p> @endif
    </div>

    <div class="form-group col-md-6 @if ($errors->has('dis_identificador')) has-error @endif">
        <label for="dis_identificador" class="control-label">Identificador do dispositivo*</label>
        <input type="text" name="dis_identificador" class="form-control" value="{{ old('dis_identificador', isset($dispositivo) ? $dispositivo->dis_identificador : null) }}">
        @if ($errors->has('dis_identificador')) <p class="help-block">{{ $errors->first('dis_identificador') }}</p> @endif
    </div>
</div>

<div class="row">
    <div class="form-group col-md-3 @if ($errors->has('dis_tipo')) has-error @endif">
        <label for="dis_tipo" class="control-label">Tipo*</label>
        <select name="dis_tipo" class="form-control">
            <option value="">Selecione</option>
            @foreach(['entrada' => 'Entrada', 'saida' => 'Saída'] as $key => $value)
                <option value="{{ $key }}" {{ old('dis_tipo', isset($dispositivo) ? $dispositivo->dis_tipo : null) === $key ? 'selected' : '' }}>{{ $value }}</option>
            @endforeach
        </select>
        @if ($errors->has('dis_tipo')) <p class="help-block">{{ $errors->first('dis_tipo') }}</p> @endif
    </div>

    <div class="form-group col-md-3 @if ($errors->has('dis_status')) has-error @endif">
        <label for="dis_status" class="control-label">Status*</label>
        <select name="dis_status" class="form-control">
            @foreach(['ativo' => 'Ativo', 'inativo' => 'Inativo'] as $key => $value)
                <option value="{{ $key }}" {{ old('dis_status', isset($dispositivo) ? $dispositivo->dis_status : 'ativo') === $key ? 'selected' : '' }}>{{ $value }}</option>
            @endforeach
        </select>
        @if ($errors->has('dis_status')) <p class="help-block">{{ $errors->first('dis_status') }}</p> @endif
    </div>

    <div class="form-group col-md-3 @if ($errors->has('dis_ip')) has-error @endif">
        <label for="dis_ip" class="control-label">IP</label>
        <input type="text" name="dis_ip" class="form-control" value="{{ old('dis_ip', isset($dispositivo) ? $dispositivo->dis_ip : null) }}">
        @if ($errors->has('dis_ip')) <p class="help-block">{{ $errors->first('dis_ip') }}</p> @endif
    </div>

    <div class="form-group col-md-3 @if ($errors->has('dis_modelo')) has-error @endif">
        <label for="dis_modelo" class="control-label">Modelo</label>
        <input type="text" name="dis_modelo" class="form-control" value="{{ old('dis_modelo', isset($dispositivo) ? $dispositivo->dis_modelo : null) }}">
        @if ($errors->has('dis_modelo')) <p class="help-block">{{ $errors->first('dis_modelo') }}</p> @endif
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('dis_observacao')) has-error @endif">
        <label for="dis_observacao" class="control-label">Observação</label>
        <textarea name="dis_observacao" class="form-control" rows="4">{{ old('dis_observacao', isset($dispositivo) ? $dispositivo->dis_observacao : null) }}</textarea>
        @if ($errors->has('dis_observacao')) <p class="help-block">{{ $errors->first('dis_observacao') }}</p> @endif
    </div>
</div>

@if(isset($dispositivo))
    <div class="row">
        <div class="form-group col-md-12">
            <label class="control-label">Token atual</label>
            <input type="text" class="form-control" value="{{ $dispositivo->dis_token_api }}" readonly>
        </div>
    </div>
@endif
