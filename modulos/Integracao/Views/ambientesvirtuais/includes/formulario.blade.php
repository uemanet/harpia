<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('amb_nome')) has-error @endif">
        <label for="amb_nome" class="form-label">Nome do ambiente*</label>
        <div class="controls">
            <input type="text" name="amb_nome" value="{{ old('amb_nome', $ambientevirtual->amb_nome ?? '') }}" class="form-control" >
            @if ($errors->has('amb_nome')) <p class="help-block">{{ $errors->first('amb_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('amb_versao')) has-error @endif">
        <label for="amb_versao" class="form-label">Versão*</label>
        <div class="controls">
            <input type="text" name="amb_versao" value="{{ old('amb_versao', $ambientevirtual->amb_nome ?? '') }}" class="form-control" >
            @if ($errors->has('amb_versao')) <p class="help-block">{{ $errors->first('amb_versao') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-12 @if ($errors->has('amb_url')) has-error @endif">
        <label for="amb_url" class="form-label">Url*</label>
        <div class="controls">
            <input type="text" name="amb_url" value="{{ old('amb_url', $ambientevirtual->amb_nome ?? '') }}" class="form-control" >
            @if ($errors->has('amb_url')) <p class="help-block">{{ $errors->first('amb_url') }}</p> @endif
        </div>
    </div>
</div>
