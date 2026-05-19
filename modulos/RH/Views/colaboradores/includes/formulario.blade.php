<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('col_data_admissao')) has-error @endif">
        <label for="col_data_admissao" class="control-label">Data de admissão*</label>
        <div class="controls">
            <input type="text" name="col_data_admissao" value="{{ old('col_data_admissao', isset($colaborador->col_data_admissao) ? $colaborador->col_data_admissao : null) }}" class="form-control datepicker" data-provide="datepicker" date-date-format="dd/mm/yyyy" >
            @if ($errors->has('col_data_admissao')) <p
                    class="help-block">{{ $errors->first('col_data_admissao') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('col_ch_diaria')) has-error @endif">
        <label for="col_ch_diaria" class="control-label">Carga Horária diária*</label>
        <div class="controls">
            <input type="number" name="col_ch_diaria" value="{{ old('col_ch_diaria', isset($colaborador->col_ch_diaria) ? $colaborador->col_ch_diaria : null) }}" class="form-control" >
            @if ($errors->has('col_ch_diaria')) <p class="help-block">{{ $errors->first('col_ch_diaria') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('col_codigo_catraca')) has-error @endif">
        <label for="col_codigo_catraca" class="control-label">Código da Catraca*</label>
        <div class="controls">
            <input type="text" name="col_codigo_catraca" value="{{ old('col_codigo_catraca', isset($colaborador->col_codigo_catraca) ? $colaborador->col_codigo_catraca : null) }}" class="form-control" >
            @if ($errors->has('col_codigo_catraca')) <p
                    class="help-block">{{ $errors->first('col_codigo_catraca') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('col_vinculo_universidade')) has-error @endif">
        <label for="col_vinculo_universidade" class="control-label">Vínculo com a universidade?*</label>
        <div class="controls">
            <select name="col_vinculo_universidade" class="form-control">
    <option value="">Selecione</option>
    @foreach(array('0' => 'Não', '1' => 'Sim') as $key => $value)
        <option value="{{ $key }}" {{ old('col_vinculo_universidade', isset($colaborador->col_vinculo_universidade) ? $colaborador->col_vinculo_universidade : null) == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
            @if ($errors->has('col_vinculo_universidade')) <p
                    class="help-block">{{ $errors->first('col_vinculo_universidade') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('col_matricula_universidade')) has-error @endif">
        <label for="col_matricula_universidade" class="control-label">Código da matrícula na universidade*</label>
        <div class="controls">
            <input type="text" name="col_matricula_universidade" value="{{ old('col_matricula_universidade', isset($colaborador->col_matricula_universidade) ? $colaborador->col_matricula_universidade : null) }}" class="form-control" >
            @if ($errors->has('col_matricula_universidade')) <p
                    class="help-block">{{ $errors->first('col_matricula_universidade') }}</p> @endif
        </div>
    </div>

    <div class="form-group col-md-4 @if ($errors->has('col_qtd_filho')) has-error @endif">
        <label for="col_qtd_filho" class="control-label">Quantidade de filhos*</label>
        <div class="controls">
            <input type="number" name="col_qtd_filho" value="{{ old('col_qtd_filho', isset($colaborador->col_qtd_filho) ? $colaborador->col_qtd_filho : null) }}" class="form-control" >
            @if ($errors->has('col_qtd_filho')) <p class="help-block">{{ $errors->first('col_qtd_filho') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('col_foto_facial')) has-error @endif">
        <label for="col_foto_facial" class="control-label">Foto facial</label>
        <div class="controls">
            <input type="file" name="col_foto_facial" class="form-control" accept=".jpg,.jpeg,.png,image/jpeg,image/png">
            <p class="help-block">Esta foto sera usada automaticamente no cadastro do colaborador nos dispositivos faciais.</p>
            @if ($errors->has('col_foto_facial')) <p class="help-block">{{ $errors->first('col_foto_facial') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('col_observacao')) has-error @endif">
        <label for="col_observacao" class="control-label">Observação</label>
        <div class="controls">
            <textarea name="col_observacao" class="form-control" rows="3">{{ old('col_observacao', isset($colaborador->col_observacao) ? $colaborador->col_observacao : null) }}</textarea>
            @if ($errors->has('col_observacao')) <p class="help-block">{{ $errors->first('col_observacao') }}</p> @endif
        </div>
    </div>
</div>
