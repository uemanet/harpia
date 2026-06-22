<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('prm_nome')) has-error @endif">
        <label for="prm_nome" class="form-label">Nome*</label>
        <div class="controls">
            <input type="text" name="prm_nome" value="{{ old('prm_nome') }}" class="form-control" >
            @if ($errors->has('prm_nome')) <p class="help-block">{{ $errors->first('prm_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('prm_rota')) has-error @endif">
        <label for="prm_rota" class="form-label">Rota*</label>
        <div class="controls">
            <input type="text" name="prm_rota" value="{{ old('prm_rota') }}" class="form-control" >
            @if ($errors->has('prm_rota')) <p class="help-block">{{ $errors->first('prm_rota') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('prm_descricao')) has-error @endif">
        <label for="prm_descricao" class="form-label">Descrição</label>
        <div class="controls">
            <input type="text" name="prm_descricao" value="{{ old('prm_descricao') }}" class="form-control" >
            @if ($errors->has('prm_descricao')) <p class="help-block">{{ $errors->first('prm_descricao') }}</p> @endif
        </div>
    </div>
</div>