<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('per_nome')) has-error @endif">
        <label for="per_nome" class="control-label">Nome para o período letivo*</label>
        <div class="controls">
            <input type="text" name="per_nome" value="{{ old('per_nome') }}" class="form-control" >
            @if ($errors->has('per_nome')) <p class="help-block">{{ $errors->first('per_nome') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('per_inicio')) has-error @endif">
        <label for="per_inicio" class="control-label">Data de Início*</label>
        <div class="controls">
            <input type="text" name="per_inicio" value="{{ old('per_inicio') }}" class="form-control datepicker" data-provide="datepicker" date-date-format="dd/mm/yyyy" >
            @if ($errors->has('per_inicio')) <p class="help-block">{{ $errors->first('per_inicio') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('per_fim')) has-error @endif">
        <label for="per_fim" class="control-label">Data de Encerramento*</label>
        <div class="controls">
            <input type="text" name="per_fim" value="{{ old('per_fim') }}" class="form-control datepicker" data-provide="datepicker" date-date-format="dd/mm/yyyy" >
            @if ($errors->has('per_fim')) <p class="help-block">{{ $errors->first('per_fim') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <button type="submit" class="btn btn-primary pull-right">Salvar dados</button>
    </div>
</div>