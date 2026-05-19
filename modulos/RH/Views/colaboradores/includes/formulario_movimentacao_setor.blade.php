<div class="row">
    <div class="form-group col-md-3 @if ($errors->has('col_set_id')) has-error @endif">
        <label for="col_set_id" class="control-label">Setor*</label>
        <div class="controls">
            <select name="col_set_id" class="form-control">
    <option value="">Selecione o setor</option>
    @foreach($setores as $key => $value)
        <option value="{{ $key }}" {{ old('col_set_id', isset($colaborador->col_set_id) ? $colaborador->col_set_id : null) == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
            @if ($errors->has('col_set_id')) <p class="help-block">{{ $errors->first('col_set_id') }}</p> @endif
        </div>
    </div>

    <div class="form-group col-md-3">
        <div class="controls">
            <button type="submit" class="btn btn-primary float-start">Atualizar setor</button>
        </div>
    </div>
</div>