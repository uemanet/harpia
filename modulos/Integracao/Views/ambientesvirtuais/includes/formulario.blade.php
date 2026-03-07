<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('amb_nome')) has-error @endif">
        <label for="amb_nome" class="control-label">Nome do ambiente*</label>
        <div class="controls">
            <input type="text" name="amb_nome" value="{{ old('amb_nome') }}" class="form-control" >
            @if ($errors->has('amb_nome')) <p class="help-block">{{ $errors->first('amb_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('amb_versao')) has-error @endif">
        <label for="amb_versao" class="control-label">Versão*</label>
        <div class="controls">
            <input type="text" name="amb_versao" value="{{ old('amb_versao') }}" class="form-control" >
            @if ($errors->has('amb_versao')) <p class="help-block">{{ $errors->first('amb_versao') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-12 @if ($errors->has('amb_url')) has-error @endif">
        <label for="amb_url" class="control-label">Url*</label>
        <div class="controls">
            <input type="text" name="amb_url" value="{{ old('amb_url') }}" class="form-control" >
            @if ($errors->has('amb_url')) <p class="help-block">{{ $errors->first('amb_url') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <button type="submit" class="btn btn-primary pull-right">Salvar dados</button>
    </div>
</div>
