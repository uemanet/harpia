<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('usr_usuario')) has-error @endif">
        <label for="usr_usuario" class="control-label">Usuário de acesso*</label>
        <div class="controls">
            <input type="text" name="usr_usuario" value="{{ old('usr_usuario') }}" class="form-control" >
            @if ($errors->has('usr_usuario')) <p class="help-block">{{ $errors->first('usr_usuario') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('usr_senha')) has-error @endif">
        <label for="usr_senha" class="control-label">Senha*</label>
        <div class="controls">
            <input type="password" name="usr_senha" >
            @if ($errors->has('usr_senha')) <p class="help-block">{{ $errors->first('usr_senha') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('usr_ativo')) has-error @endif">
        <label for="usr_ativo" class="control-label">Ativo*</label>
        <div class="controls">
            <select name="usr_ativo" class="form-control">
    <option value="1" {{ old('usr_ativo', 1) == 1 ? 'selected' : '' }}>Sim</option>
    <option value="0" {{ old('usr_ativo', 1) == 0 ? 'selected' : '' }}>Não</option>
</select>
            @if ($errors->has('usr_ativo')) <p class="help-block">{{ $errors->first('usr_ativo') }}</p> @endif
        </div>
    </div>
</div>