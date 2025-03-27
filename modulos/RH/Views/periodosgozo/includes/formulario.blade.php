<div class="row">

    <div class="form-group col-md-4 @if ($errors->has('paq_mtc_id')) has-error @endif">
        {!! Form::label('pgz_paq_id', 'Periodo Aquisitivo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('pgz_paq_id', $periodos, old('pgz_paq_id'), ['class' => 'form-control', 'placeholder' => 'Selecione o período aquisitivo']) !!}
            @if ($errors->has('pgz_paq_id')) <p class="help-block">{{ $errors->first('pgz_paq_id') }}</p> @endif
        </div>
    </div>

    <div class="form-group col-md-4 @if ($errors->has('pgz_data_inicio')) has-error @endif">
        {!! Form::label('pgz_data_inicio', 'Data de Início', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pgz_data_inicio',old('pgz_data_inicio'), ['class' => 'form-control datepicker', 'data-provide' => 'datepicker', 'date-date-format' => 'dd/mm/yyyy']) !!}
            @if ($errors->has('pgz_data_inicio')) <p
                    class="help-block">{{ $errors->first('pgz_data_inicio') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('pgz_data_fim')) has-error @endif">
        {!! Form::label('pgz_data_fim', 'Data de Fim', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pgz_data_fim',old('pgz_data_fim'), ['class' => 'form-control datepicker', 'data-provide' => 'datepicker', 'date-date-format' => 'dd/mm/yyyy']) !!}
            @if ($errors->has('pgz_data_fim')) <p
                    class="help-block">{{ $errors->first('pgz_data_fim') }}</p> @endif
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