<div class="row">
    <div class="form-group col-md-3 @if ($errors->has('ccb_ban_id')) has-error @endif">
        <label for="ccb_ban_id" class="control-label">Banco*</label>
        <div class="controls">
            <select name="ccb_ban_id" class="form-control">
    <option value="">Selecione o banco</option>
    @foreach($bancos as $key => $value)
        <option value="{{ $key }}" {{ old('ccb_ban_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
            @if ($errors->has('ccb_ban_id')) <p class="help-block">{{ $errors->first('ccb_ban_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('ccb_agencia')) has-error @endif">
        <label for="ccb_agencia" class="control-label">Agência*</label>
        <div class="controls">
            <input type="text" name="ccb_agencia" value="{{ old('ccb_agencia') }}" class="form-control" >
            @if ($errors->has('ccb_agencia')) <p class="help-block">{{ $errors->first('ccb_agencia') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('ccb_conta')) has-error @endif">
      <label for="ccb_conta" class="control-label">Conta*</label>
      <div class="controls">
        <input type="text" name="ccb_conta" value="{{ old('ccb_conta') }}" class="form-control" >
        @if ($errors->has('ccb_conta')) <p class="help-block">{{ $errors->first('ccb_conta') }}</p> @endif
      </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('ccb_variacao')) has-error @endif">
        <label for="ccb_variacao" class="control-label">Variação*</label>
        <div class="controls">
            <input type="text" name="ccb_variacao" value="{{ old('ccb_variacao') }}" class="form-control" >
            @if ($errors->has('ccb_variacao')) <p class="help-block">{{ $errors->first('ccb_variacao') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12">
        <label class="control-label" style="visibility: hidden">Botão</label>
        <div class="controls">
            <button type="submit" class="btn btn-primary float-end">Salvar dados</button>
        </div>
    </div>
</div>
