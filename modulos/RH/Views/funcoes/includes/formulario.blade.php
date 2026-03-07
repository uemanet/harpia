<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('fun_descricao')) has-error @endif">
        <label for="fun_descricao" class="control-label">Função*</label>
        <div class="controls">
            <input type="text" name="fun_descricao" value="{{ old('fun_descricao') }}" class="form-control" >
            @if ($errors->has('fun_descricao')) <p class="help-block">{{ $errors->first('fun_descricao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <button type="submit" class="btn btn-primary pull-right">Salvar dados</button>
    </div>
</div>