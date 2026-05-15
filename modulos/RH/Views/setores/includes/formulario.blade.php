<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('set_descricao')) has-error @endif">
        <label for="set_descricao" class="form-label">Descrição*</label>
        <div class="controls">
            <input type="text" name="set_descricao" value="{{ old('set_descricao', $setor->set_descricao ?? '') }}" class="form-control" >
            @if ($errors->has('set_descricao')) <p class="help-block">{{ $errors->first('set_descricao') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('set_sigla')) has-error @endif">
        <label for="set_sigla" class="form-label">Sigla*</label>
        <div class="controls">
            <input type="text" name="set_sigla" value="{{ old('set_sigla', $setor->set_sigla ?? '') }}" class="form-control" >
            @if ($errors->has('set_sigla')) <p class="help-block">{{ $errors->first('set_sigla') }}</p> @endif
        </div>
    </div>
</div>