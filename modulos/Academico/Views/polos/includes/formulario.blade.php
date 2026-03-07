<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('pol_nome')) has-error @endif">
        <label for="pol_nome" class="control-label">Nome do polo*</label>
        <div class="controls">
            <input type="text" name="pol_nome" value="{{ old('pol_nome') }}" class="form-control" >
            @if ($errors->has('pol_nome')) <p class="help-block">{{ $errors->first('pol_nome') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <button type="submit" class="btn btn-primary pull-right">Salvar dados</button>
    </div>
</div>