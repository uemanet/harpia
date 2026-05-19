<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('vfp_vin_id')) has-error @endif">
        <label for="vfp_vin_id" class="control-label">Vínculo*</label>
        <div class="controls">
            <select name="vfp_vin_id" class="form-control">
    <option value="">Selecione o tipo de vínculo</option>
    @foreach($vinculos as $key => $value)
        <option value="{{ $key }}" {{ old('vfp_vin_id', $vinculo_fpg->vfp_vin_id) == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
            @if ($errors->has('vfp_vin_id')) <p class="help-block">{{ $errors->first('vfp_vin_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('vfp_unidade')) has-error @endif">
        <label for="vfp_unidade" class="control-label">Pagamento por unidade*</label>
        <div class="controls">
            <select name="vfp_unidade" class="form-control">
    <option value="">Selecione</option>
    @foreach(array(0 => 'Não', 1 => 'Sim') as $key => $value)
        <option value="{{ $key }}" {{ old('vfp_unidade', $vinculo_fpg->vfp_unidade) == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
            @if ($errors->has('vfp_unidade')) <p class="help-block">{{ $errors->first('vfp_unidade') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('vfp_valor')) has-error @endif">
        <label for="vfp_valor" class="control-label">Valor (R$)*</label>
        <div class="controls">
            <input type="text" name="vfp_valor" value="{{ old('vfp_valor', $vinculo_fpg->vfp_valor) }}" class="form-control" onkeyup="k(this);" >
            @if ($errors->has('vfp_valor')) <p class="help-block">{{ $errors->first('vfp_valor') }}</p> @endif
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
