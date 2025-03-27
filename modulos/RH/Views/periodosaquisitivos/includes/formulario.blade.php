<div class="row">

    <div class="form-group col-md-4 @if ($errors->has('paq_mtc_id')) has-error @endif">
        {!! Form::label('paq_mtc_id', 'Matrícula*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('paq_mtc_id', $matriculas, old('paq_mtc_id'), ['class' => 'form-control', 'placeholder' => 'Selecione a matrícula']) !!}
            @if ($errors->has('paq_mtc_id')) <p class="help-block">{{ $errors->first('paq_mtc_id') }}</p> @endif
        </div>
    </div>

    @php
        $selected = old('paq_periodo_aquisitivo', isset($periodo_aquisitivo) && !empty($periodo_aquisitivo->paq_gozo_inicio) && !empty($periodo_aquisitivo->paq_gozo_fim)
            ? $periodo_aquisitivo->paq_gozo_inicio . '|' . $periodo_aquisitivo->paq_gozo_fim
            : '');
    @endphp

    <div class="form-group col-md-5">
        {!! Form::label('paq_periodo_aquisitivo', 'Período de Gozo') !!}
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
        {!! Form::label('paq_data_inicio', 'Data de Início', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('paq_data_inicio',old('paq_data_inicio'), ['class' => 'form-control datepicker', 'data-provide' => 'datepicker', 'date-date-format' => 'dd/mm/yyyy']) !!}
            @if ($errors->has('paq_data_inicio')) <p
                    class="help-block">{{ $errors->first('paq_data_inicio') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('paq_data_fim')) has-error @endif">
        {!! Form::label('paq_data_fim', 'Data de Fim', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('paq_data_fim',old('paq_data_fim'), ['class' => 'form-control datepicker', 'data-provide' => 'datepicker', 'date-date-format' => 'dd/mm/yyyy']) !!}
            @if ($errors->has('paq_data_fim')) <p
                    class="help-block">{{ $errors->first('paq_data_fim') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('paq_observacao')) has-error @endif">
        {!! Form::label('paq_observacao', 'Observação', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('paq_observacao', old('paq_observacao'), ['class' => 'form-control']) !!}
            @if ($errors->has('paq_observacao')) <p class="help-block">{{ $errors->first('paq_observacao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <label class="control-label" style="visibility: hidden">Botão</label>
        <div class="controls">
            {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
        </div>
    </div>
</div>