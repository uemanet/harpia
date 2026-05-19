<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('ban_nome')) has-error @endif">
        <label for="ban_nome" class="control-label">Nome do banco*</label>
        <div class="controls">
            <input type="text" name="ban_nome" value="{{ old('ban_nome', $banco->ban_nome) }}" class="form-control" >
            @if ($errors->has('ban_nome')) <p class="help-block">{{ $errors->first('ban_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('ban_sigla')) has-error @endif">
        <label for="ban_sigla" class="control-label">Sigla*</label>
        <div class="controls">
            <input type="text" name="ban_sigla" value="{{ old('ban_sigla', $banco->ban_sigla) }}" class="form-control" >
            @if ($errors->has('ban_sigla')) <p class="help-block">{{ $errors->first('ban_sigla') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('ban_codigo')) has-error @endif">
        <label for="ban_codigo" class="control-label">Código*</label>
        <div class="controls">
            <input type="text" name="ban_codigo" value="{{ old('ban_codigo', $banco->ban_codigo) }}" class="form-control" >
            @if ($errors->has('ban_codigo')) <p class="help-block">{{ $errors->first('ban_codigo') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <button type="submit" class="btn btn-primary float-end">Salvar dados</button>
    </div>
</div>