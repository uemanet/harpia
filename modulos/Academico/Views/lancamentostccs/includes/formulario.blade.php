<div class="row">
    <input type="hidden" name="trm_id" value="{{ $turma->trm_id }}" >
    <div class="form-group col-md-12 @if ($errors->has('ltc_titulo')) has-error @endif">
        <label for="ltc_titulo" class="control-label">Título do TCC*</label>
        <div class="controls">
            <input type="text" name="ltc_titulo" value="{{ old('ltc_titulo') }}" class="form-control" >
            @if ($errors->has('ltc_titulo')) <p class="help-block">{{ $errors->first('ltc_titulo') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('ltc_prf_id')) has-error @endif">
        <label for="ltc_prf_id" class="control-label">Professor*</label>
        <div class="controls">
            <select name="ltc_prf_id" class="form-control">
    <option value="">Selecione um professor</option>
    @foreach($professores as $key => $value)
        <option value="{{ $key }}" {{ old('ltc_prf_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
            @if ($errors->has('ltc_prf_id')) <p class="help-block">{{ $errors->first('ltc_prf_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('ltc_tipo')) has-error @endif">
        <label for="ltc_tipo" class="control-label">Tipo de TCC*</label>
        <div class="controls">
            <select name="ltc_tipo" class="form-control">
    <option value="">Selecione um tipo</option>
    @foreach($tiposdetcc as $key => $value)
        <option value="{{ $key }}" {{ old('ltc_tipo') == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
            @if ($errors->has('ltc_tipo')) <p class="help-block">{{ $errors->first('ltc_tipo') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('ltc_file')) has-error @endif">
        <label for="ltc_file" class="control-label">Documento</label>
        <div class="controls">
            <input type="file" name="ltc_file" class="form-control file" >
            @if ($errors->has('ltc_file')) <p class="help-block">{{ $errors->first('ltc_file') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('ltc_data_apresentacao')) has-error @endif">
        <label for="ltc_data_apresentacao" class="control-label">Data de apresentação*</label>
        <div class="controls">
            <input type="text" name="ltc_data_apresentacao" value="{{ old('ltc_data_apresentacao') }}" class="form-control datepicker" data-provide="datepicker" date-date-format="dd/mm/yyyy" >
            @if ($errors->has('ltc_data_apresentacao')) <p
                    class="help-block">{{ $errors->first('ltc_data_apresentacao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('ltc_observacao')) has-error @endif">
        <label for="ltc_observacao" class="control-label">Observação</label>
        <div class="controls">
            <textarea name="ltc_observacao" class="form-control" rows="4">{{ old('ltc_observacao') }}</textarea>
            @if ($errors->has('ltc_observacao')) <p class="help-block">{{ $errors->first('ltc_observacao') }}</p> @endif
        </div>
    </div>
</div>
<input type="hidden" name="ltc_mof_id" value="{{ $matriculaoferta }}" class="form-control" >
<div class="row">
    <div class="form-group col-md-offset-8 col-md-4">
        <button type="submit" class="btn btn-primary pull-right">Salvar dados</button>
    </div>
</div>
