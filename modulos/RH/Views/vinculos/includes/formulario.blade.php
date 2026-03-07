<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('vin_descricao')) has-error @endif">
        <label for="vin_descricao" class="control-label">Descrição*</label>
        <div class="controls">
            <input type="text" name="vin_descricao" value="{{ old('vin_descricao') }}" class="form-control" >
            @if ($errors->has('vin_descricao')) <p class="help-block">{{ $errors->first('vin_descricao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <button type="submit" class="btn btn-primary pull-right">Salvar dados</button>
    </div>
</div>