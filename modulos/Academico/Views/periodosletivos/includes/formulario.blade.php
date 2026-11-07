<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('per_nome')) has-error @endif">
        <label for="per_nome" class="form-label">Nome para o período letivo <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="text" name="per_nome" value="{{ old('per_nome', $periodoLetivo->per_nome ?? '') }}" class="form-control" >
            @if ($errors->has('per_nome')) <p class="help-block">{{ $errors->first('per_nome') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('per_inicio')) has-error @endif">
        <label for="per_inicio" class="form-label">Data de Início <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="text" name="per_inicio" value="{{ old('per_inicio', $periodoLetivo->per_inicio ?? '') }}" class="form-control datepicker" data-provide="datepicker" date-date-format="dd/mm/yyyy" >
            @if ($errors->has('per_inicio')) <p class="help-block">{{ $errors->first('per_inicio') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('per_fim')) has-error @endif">
        <label for="per_fim" class="form-label">Data de Encerramento <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="text" name="per_fim" value="{{ old('per_fim', $periodoLetivo->per_fim ?? '') }}" class="form-control datepicker" data-provide="datepicker" date-date-format="dd/mm/yyyy" >
            @if ($errors->has('per_fim')) <p class="help-block">{{ $errors->first('per_fim') }}</p> @endif
        </div>
    </div>
</div>