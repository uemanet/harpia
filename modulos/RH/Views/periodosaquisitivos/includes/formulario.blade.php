<div class="row">

    <div class="form-group col-md-4 @if ($errors->has('paq_mtc_id')) has-error @endif">
        <label for="paq_mtc_id" class="form-label">Matrícula*</label>
        <div class="controls">
            <select name="paq_mtc_id" class="form-control">
    <option value="">Selecione a matrícula</option>
    @foreach($matriculas as $key => $value)
        <option value="{{ $key }}" {{ old('paq_mtc_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
            @if ($errors->has('paq_mtc_id')) <p class="help-block">{{ $errors->first('paq_mtc_id') }}</p> @endif
        </div>
    </div>

    @php
        $selected = old('paq_periodo_aquisitivo', isset($periodo_aquisitivo) && !empty($periodo_aquisitivo->paq_gozo_inicio) && !empty($periodo_aquisitivo->paq_gozo_fim)
            ? $periodo_aquisitivo->paq_gozo_inicio . '|' . $periodo_aquisitivo->paq_gozo_fim
            : '');
    @endphp

    <div class="form-group col-md-5">
        <label for="paq_periodo_aquisitivo">Período de Gozo</label>
        <select name="paq_periodo_aquisitivo" class="form-control">
            @foreach($periodosDisponiveis as $periodo)
                @php $value = $periodo['inicio'] . '|' . $periodo['fim']; @endphp
                <option value="{{ $value }}" {{ $value == $selected ? 'selected' : '' }}>
                    Período de {{ $periodo['inicio'] }} a {{ $periodo['fim'] }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-md-4 @if ($errors->has('paq_data_inicio')) has-error @endif">
        <label for="paq_data_inicio" class="form-label">Data de Início</label>
        <div class="controls">
            <input type="text" name="paq_data_inicio" value="{{ old('paq_data_inicio') }}" class="form-control datepicker" data-provide="datepicker" date-date-format="dd/mm/yyyy" >
            @if ($errors->has('paq_data_inicio')) <p
                    class="help-block">{{ $errors->first('paq_data_inicio') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('paq_data_fim')) has-error @endif">
        <label for="paq_data_fim" class="form-label">Data de Fim</label>
        <div class="controls">
            <input type="text" name="paq_data_fim" value="{{ old('paq_data_fim') }}" class="form-control datepicker" data-provide="datepicker" date-date-format="dd/mm/yyyy" >
            @if ($errors->has('paq_data_fim')) <p
                    class="help-block">{{ $errors->first('paq_data_fim') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('paq_observacao')) has-error @endif">
        <label for="paq_observacao" class="form-label">Observação</label>
        <div class="controls">
            <input type="text" name="paq_observacao" value="{{ old('paq_observacao') }}" class="form-control" >
            @if ($errors->has('paq_observacao')) <p class="help-block">{{ $errors->first('paq_observacao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <label class="form-label" style="visibility: hidden">Botão</label>
        <div class="controls">
            <button type="submit" class="btn btn-primary pull-right">Salvar dados</button>
        </div>
    </div>
</div>