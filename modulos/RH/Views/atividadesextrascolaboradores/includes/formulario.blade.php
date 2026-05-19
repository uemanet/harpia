<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('atc_titulo')) has-error @endif">
        <label for="atc_titulo" class="control-label">Título*</label>
        <div class="controls">
            <input type="text" name="atc_titulo" value="{{ old('atc_titulo', $atividade_extra->atc_titulo) }}" class="form-control" >
            @if ($errors->has('atc_titulo')) <p class="help-block">{{ $errors->first('atc_titulo') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('atc_descricao')) has-error @endif">
      <label for="atc_descricao" class="control-label">Descrição</label>
      <div class="controls">
        <input type="text" name="atc_descricao" value="{{ old('atc_descricao', $atividade_extra->atc_descricao) }}" class="form-control" >
        @if ($errors->has('atc_descricao')) <p class="help-block">{{ $errors->first('atc_descricao') }}</p> @endif
      </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('atc_tipo')) has-error @endif">
        <label for="atc_tipo" class="control-label">Tipo*</label>
        <div class="controls">
            <select name="atc_tipo" class="form-control">
    <option value="">Selecione</option>
    @foreach(array('curso' => 'curso', 'evento' => 'evento', 'oficina' => 'oficina') as $key => $value)
        <option value="{{ $key }}" {{ old('atc_tipo', $atividade_extra->atc_tipo) == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
            @if ($errors->has('atc_tipo')) <p class="help-block">{{ $errors->first('atc_tipo') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('atc_carga_horaria')) has-error @endif">
        <label for="atc_carga_horaria" class="control-label">Carga Horária</label>
        <div class="controls">
            <input type="number" name="atc_carga_horaria" value="{{ old('atc_carga_horaria', $atividade_extra->atc_carga_horaria) }}" min="1" max="9999" maxlength="4" class="form-control" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" >
            @if ($errors->has('atc_carga_horaria')) <p class="help-block">{{ $errors->first('atc_carga_horaria') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('atc_data_inicio')) has-error @endif">
        <label for="atc_data_inicio" class="control-label">Data de Início</label>
        <div class="controls">
            <input type="text" name="atc_data_inicio" value="{{ old('atc_data_inicio', $atividade_extra->atc_data_inicio) }}" class="form-control datepicker" data-provide="datepicker" date-date-format="dd/mm/yyyy" >
            @if ($errors->has('atc_data_inicio')) <p
                    class="help-block">{{ $errors->first('atc_data_inicio') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('atc_data_fim')) has-error @endif">
        <label for="atc_data_fim" class="control-label">Data de Conclusão</label>
        <div class="controls">
            <input type="text" name="atc_data_fim" value="{{ old('atc_data_fim', $atividade_extra->atc_data_fim) }}" class="form-control datepicker" data-provide="datepicker" date-date-format="dd/mm/yyyy" >
            @if ($errors->has('atc_data_fim')) <p
                    class="help-block">{{ $errors->first('atc_data_fim') }}</p> @endif
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
