<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('mtc_data_inicio')) has-error @endif">
        <label for="mtc_data_inicio" class="control-label">Data de admissão*</label>
        <div class="controls">
            <input type="text" name="mtc_data_inicio" value="{{ old('mtc_data_inicio', isset($colaborador->mtc_data_inicio) ? $colaborador->mtc_data_inicio : null) }}" class="form-control datepicker" data-provide="datepicker" date-date-format="dd/mm/yyyy" >
            @if ($errors->has('mtc_data_inicio')) <p
                    class="help-block">{{ $errors->first('mtc_data_inicio') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <label class="control-label" style="visibility: hidden">Botão</label>
        <div class="controls">
            <button type="submit" class="btn btn-primary float-start">Salvar dados</button>
        </div>
    </div>
</div>
