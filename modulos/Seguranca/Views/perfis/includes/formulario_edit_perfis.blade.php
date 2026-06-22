<div class="row">
    <div class="form-group col-md-3 @if ($errors->has('prf_mod_id')) has-error @endif">
        <label for="prf_mod_id" class="control-label">Módulo*</label>
        <div class="controls">
            <select name="prf_mod_id" class="form-control" id="mod_id">
                <option value="">Selecione um módulo</option>
                @foreach($modulos as $key => $value)
                    <option value="{{ $key }}" {{ old('prf_mod_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
            @if ($errors->has('prf_mod_id')) <p class="help-block">{{ $errors->first('prf_mod_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-9 @if ($errors->has('prf_nome')) has-error @endif">
        <label for="prf_nome" class="control-label">Nome do perfil*</label>
        <div class="controls">
            <input type="text" name="prf_nome" value="{{ old('prf_nome') }}" class="form-control" >
            @if ($errors->has('prf_nome')) <p class="help-block">{{ $errors->first('prf_nome') }}</p> @endif
        </div>
    </div>
</div>
<div class="form-group @if ($errors->has('prf_descricao')) has-error @endif">
    <label for="prf_descricao" class="control-label">Descrição do perfil</label>
    <div class="controls">
        <input type="text" name="prf_descricao" value="{{ old('prf_descricao') }}" class="form-control" >
        @if ($errors->has('prf_descricao')) <p class="help-block">{{ $errors->first('prf_descricao') }}</p> @endif
    </div>
</div>