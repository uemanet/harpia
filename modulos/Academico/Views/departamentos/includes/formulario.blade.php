<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('dep_cen_id')) has-error @endif">
        <label for="dep_cen_id" class="control-label">Centro*</label>
        <div class="controls">
            <select name="dep_cen_id" class="form-control select-control">
    <option value="">Selecione o centro</option>
    @foreach($centros as $key => $value)
        <option value="{{ $key }}" {{ old('dep_cen_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
            @if ($errors->has('dep_cen_id')) <p class="help-block">{{ $errors->first('dep_cen_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('dep_prf_diretor')) has-error @endif">
        <label for="dep_prf_diretor" class="control-label">Diretor do departamento*</label>
        <div class="controls">
            <select name="dep_prf_diretor" class="form-control">
    <option value="">Selecione o diretor</option>
    @foreach($professores as $key => $value)
        <option value="{{ $key }}" {{ old('dep_prf_diretor') == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
            @if ($errors->has('dep_prf_diretor')) <p class="help-block">{{ $errors->first('dep_prf_diretor') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('dep_nome')) has-error @endif">
        <label for="dep_nome" class="control-label">Nome do departamento*</label>
        <div class="controls">
            <input type="text" name="dep_nome" value="{{ old('dep_nome') }}" class="form-control select-control" >
            @if ($errors->has('dep_nome')) <p class="help-block">{{ $errors->first('dep_nome') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <button type="submit" class="btn btn-primary pull-right">Salvar dados</button>
    </div>
</div>
