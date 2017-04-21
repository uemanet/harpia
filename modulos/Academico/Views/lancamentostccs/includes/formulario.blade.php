<div class="row">
    {!! Form::hidden('trm_id', $turma->trm_id) !!}
    <div class="form-group col-md-4 @if ($errors->has('ltc_tipo')) has-error @endif">
        {!! Form::label('ltc_tipo', 'Tipo de TCC*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('ltc_tipo', $tiposdetcc, old('ltc_tipo'), ['placeholder' => 'Selecione um tipo','class' => 'form-control']) !!}
            @if ($errors->has('ltc_tipo')) <p class="help-block">{{ $errors->first('ltc_tipo') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('ltc_titulo')) has-error @endif">
        {!! Form::label('ltc_titulo', 'Título do TCC*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('ltc_titulo', old('ltc_titulo'), ['class' => 'form-control']) !!}
            @if ($errors->has('ltc_titulo')) <p class="help-block">{{ $errors->first('ltc_titulo') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('ltc_prf_id')) has-error @endif">
        {!! Form::label('ltc_prf_id', 'Professor*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('ltc_prf_id', $professores, old('ltc_prf_id'), ['placeholder' => 'Selecione um professor','class' => 'form-control']) !!}
            @if ($errors->has('ltc_prf_id')) <p class="help-block">{{ $errors->first('ltc_prf_id') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('ltc_data_apresentacao')) has-error @endif">
        {!! Form::label('ltc_data_apresentacao', 'Data de apresentação*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('ltc_data_apresentacao', old('ltc_data_apresentacao'), ['class' => 'form-control datepicker', 'data-provide' => 'datepicker', 'date-date-format' => 'dd/mm/yyyy']) !!}
            @if ($errors->has('ltc_data_apresentacao')) <p class="help-block">{{ $errors->first('ltc_data_apresentacao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
  <div class="form-group col-md-4 @if ($errors->has('ltc_file')) has-error @endif">
    {!! Form::label('ltc_file', 'Documento', ['class' => 'control-label']) !!}
    <div class="controls">
      {!! Form::file('ltc_file', ['class' => 'form-control file']) !!}
      @if ($errors->has('ltc_file')) <p class="help-block">{{ $errors->first('ltc_file') }}</p> @endif
    </div>
  </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('ltc_observacao')) has-error @endif">
        {!! Form::label('ltc_observacao', 'Observação', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::textarea('ltc_observacao', old('ltc_observacao'), ['class' => 'form-control', 'rows' => '4']) !!}
            @if ($errors->has('ltc_observacao')) <p class="help-block">{{ $errors->first('ltc_observacao') }}</p> @endif
        </div>
    </div>
</div>

{!! Form::input('hidden' , 'mat_id', $matricula->mat_id ,  ['class' => 'form-control']) !!}

<div class="row">
    <div class="form-group col-md-4">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>
