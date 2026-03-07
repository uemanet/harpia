<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('dis_nome')) has-error @endif">
        <label for="dis_nome" class="control-label">Nome da disciplina*</label>
        <div class="controls">
            <input type="text" name="dis_nome" value="{{ old('dis_nome') }}" class="form-control" >
            @if ($errors->has('dis_nome')) <p class="help-block">{{ $errors->first('dis_nome') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('dis_carga_horaria')) has-error @endif">
        <label for="dis_carga_horaria" class="control-label">Carga-Horária*</label>
        <div class="controls">
            <input type="number" name="dis_carga_horaria" value="{{ old('dis_carga_horaria') }}" class="form-control" >
            @if ($errors->has('dis_carga_horaria')) <p
                    class="help-block">{{ $errors->first('dis_carga_horaria') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('dis_creditos')) has-error @endif">
        <label for="dis_creditos" class="control-label">Créditos*</label>
        <div class="controls">
            <input type="number" name="dis_creditos" value="{{ old('dis_creditos') }}" class="form-control" >
            @if ($errors->has('dis_creditos')) <p class="help-block">{{ $errors->first('dis_creditos') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('dis_nvc_id')) has-error @endif">
        <label for="dis_nvc_id" class="control-label">Nível*</label>
        <div class="controls">
            <select name="dis_nvc_id" class="form-control">
    <option value="">Selecione o nível</option>
    @foreach($niveis as $key => $value)
        <option value="{{ $key }}" {{ old('dis_nvc_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
            @if ($errors->has('dis_nvc_id')) <p class="help-block">{{ $errors->first('dis_nvc_id') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('dis_ementa')) has-error @endif">
        <label for="dis_ementa" class="control-label">Ementa</label>
        <div class="controls">
            <textarea name="dis_ementa" class="form-control">{{ old('dis_ementa') }}</textarea>
            @if ($errors->has('dis_ementa')) <p class="help-block">{{ $errors->first('dis_ementa') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('dis_bibliografia')) has-error @endif">
        <label for="dis_bibliografia" class="control-label">Bibliografia</label>
        <div class="controls">
            <textarea name="dis_bibliografia" class="form-control">{{ old('dis_bibliografia') }}</textarea>
            @if ($errors->has('dis_bibliografia')) <p
                    class="help-block">{{ $errors->first('dis_bibliografia') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <button type="submit" class="btn btn-primary pull-right">Salvar dados</button>
    </div>
</div>