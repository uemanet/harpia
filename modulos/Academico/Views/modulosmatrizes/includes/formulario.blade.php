<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('crs_id')) has-error @endif">
        <label for="crs_id" class="control-label">Curso*</label>
        <div class="controls">
            <select name="crs_id" class="form-control" id="crs_id">
    @foreach($curso as $key => $value)
        <option value="{{ $key }}">{{ $value }}</option>
    @endforeach
</select>
            @if ($errors->has('crs_id')) <p class="help-block">{{ $errors->first('crs_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('mdo_mtc_id')) has-error @endif">
        <label for="mdo_mtc_id" class="control-label">Matriz*</label>
        <div class="controls">
            <select name="mdo_mtc_id" class="form-control" id="mdo_mtc_id">
    @foreach($matriz as $key => $value)
        <option value="{{ $key }}" {{ $matriz == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
            @if ($errors->has('mdo_mtc_id')) <p class="help-block">{{ $errors->first('mdo_mtc_id') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('mdo_nome')) has-error @endif">
        <label for="mdo_nome" class="control-label">Nome*</label>
        <div class="controls">
            <input type="text" name="mdo_nome" value="{{ old('mdo_nome') }}" class="form-control" >
            @if ($errors->has('mdo_nome')) <p class="help-block">{{ $errors->first('mdo_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('mdo_cargahoraria_min_eletivas')) has-error @endif">
        <label for="mdo_cargahoraria_min_eletivas" class="control-label">Carga Horária Mínima de Eletivas</label>
        <div class="controls">
            <input type="number" name="mdo_cargahoraria_min_eletivas" value="{{ old('mdo_cargahoraria_min_eletivas') }}" class="form-control" >
            @if ($errors->has('mdo_cargahoraria_min_eletivas')) <p class="help-block">{{ $errors->first('mdo_cargahoraria_min_eletivas') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('mdo_creditos_min_eletivas')) has-error @endif">
        <label for="mdo_creditos_min_eletivas" class="control-label">Créditos Mínimos de Eletivas</label>
        <div class="controls">
            <input type="number" name="mdo_creditos_min_eletivas" value="{{ old('mdo_creditos_min_eletivas') }}" class="form-control" >
            @if ($errors->has('mdo_creditos_min_eletivas')) <p class="help-block">{{ $errors->first('mdo_creditos_min_eletivas') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('mdo_descricao')) has-error @endif">
        <label for="mdo_descricao" class="control-label">Descrição*</label>
        <div class="controls">
            <textarea name="mdo_descricao" class="form-control">{{ old('mdo_descricao') }}</textarea>
            @if ($errors->has('mdo_descricao')) <p class="help-block">{{ $errors->first('mdo_descricao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('mdo_qualificacao')) has-error @endif">
        <label for="mdo_qualificacao" class="control-label">Qualificação</label>
        <div class="controls">
            <input type="text" name="mdo_qualificacao" value="{{ old('mdo_qualificacao') }}" class="form-control" >
            @if ($errors->has('mdo_qualificacao')) <p class="help-block">{{ $errors->first('mdo_qualificacao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('mdo_competencias')) has-error @endif">
        <label for="mdo_competencias" class="control-label">Competências</label>
        <div class="controls">
            <textarea name="mdo_competencias" class="form-control" rows="4">{{ old('mdo_competencias') }}</textarea>
            @if ($errors->has('mdo_competencias')) <p class="help-block">{{ $errors->first('mdo_competencias') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <button type="submit" class="btn btn-primary pull-right">Salvar dados</button>
    </div>
</div>
