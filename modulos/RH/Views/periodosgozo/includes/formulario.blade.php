<div class="row">

    <div class="form-group col-md-4 @if ($errors->has('paq_mtc_id')) has-error @endif">
        <label for="pgz_paq_id" class="control-label">Periodo Aquisitivo*</label>
        <div class="controls">
            <select name="pgz_paq_id" class="form-control">
    <option value="">Selecione o período aquisitivo</option>
    @foreach($periodos as $key => $value)
        <option value="{{ $key }}" {{ old('pgz_paq_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
            @if ($errors->has('pgz_paq_id')) <p class="help-block">{{ $errors->first('pgz_paq_id') }}</p> @endif
        </div>
    </div>

    <div class="form-group col-md-4 @if ($errors->has('pgz_data_inicio')) has-error @endif">
        <label for="pgz_data_inicio" class="control-label">Data de Início</label>
        <div class="controls">
            <input type="text" name="pgz_data_inicio" value="{{ old('pgz_data_inicio') }}" class="form-control datepicker" data-provide="datepicker" date-date-format="dd/mm/yyyy" >
            @if ($errors->has('pgz_data_inicio')) <p
                    class="help-block">{{ $errors->first('pgz_data_inicio') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('pgz_data_fim')) has-error @endif">
        <label for="pgz_data_fim" class="control-label">Data de Fim</label>
        <div class="controls">
            <input type="text" name="pgz_data_fim" value="{{ old('pgz_data_fim') }}" class="form-control datepicker" data-provide="datepicker" date-date-format="dd/mm/yyyy" >
            @if ($errors->has('pgz_data_fim')) <p
                    class="help-block">{{ $errors->first('pgz_data_fim') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('paq_observacao')) has-error @endif">
        <label for="paq_observacao" class="control-label">Observação</label>
        <div class="controls">
            <input type="text" name="paq_observacao" value="{{ old('paq_observacao') }}" class="form-control" >
            @if ($errors->has('paq_observacao')) <p class="help-block">{{ $errors->first('paq_observacao') }}</p> @endif
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