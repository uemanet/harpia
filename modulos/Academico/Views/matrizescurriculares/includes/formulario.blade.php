<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('mtc_crs_id')) has-error @endif">
        {!! Form::label('mtc_crs_id', 'Curso*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('mtc_crs_id', $curso, $cursoId, ['class' => 'form-control']) !!}
            @if ($errors->has('mtc_crs_id')) <p class="help-block">{{ $errors->first('mtc_crs_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('mtc_file')) has-error @endif">
        {!! Form::label('mtc_file', 'Projeto Pedagógico*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::file('mtc_file', ['class' => 'form-control file']) !!}
            @if ($errors->has('mtc_file')) <p class="help-block">{{ $errors->first('mtc_file') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-3 @if ($errors->has('mtc_titulo')) has-error @endif">
        {!! Form::label('mtc_titulo', 'Título*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('mtc_titulo', old('mtc_titulo'), ['class' => 'form-control select-control']) !!}
            @if ($errors->has('mtc_titulo')) <p class="help-block">{{ $errors->first('mtc_titulo') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('mtc_data')) has-error @endif">
      {!! Form::label('mtc_data', 'Data*', ['class' => 'control-label']) !!}
      <div class="controls">
        {!! Form::text('mtc_data', old('mtc_data'), ['class' => 'form-control datepicker', 'data-provide' => 'datepicker', 'date-date-format' => 'dd/mm/yyyy']) !!}
        @if ($errors->has('mtc_data')) <p class="help-block">{{ $errors->first('mtc_data') }}</p> @endif
      </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('mtc_horas')) has-error @endif">
      {!! Form::label('mtc_horas', 'Horas*', ['class' => 'control-label']) !!}
      <div class="controls">
        {!! Form::number('mtc_horas', old('mtc_horas'), ['class' => 'form-control select-control']) !!}
        @if ($errors->has('mtc_horas')) <p class="help-block">{{ $errors->first('mtc_horas') }}</p> @endif
      </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('mtc_creditos')) has-error @endif">
      {!! Form::label('mtc_creditos', 'Créditos', ['class' => 'control-label']) !!}
      <div class="controls">
        {!! Form::number('mtc_creditos', old('mtc_creditos'), ['class' => 'form-control select-control']) !!}
        @if ($errors->has('mtc_creditos')) <p class="help-block">{{ $errors->first('mtc_creditos') }}</p> @endif
      </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-3 @if ($errors->has('mtc_horas_praticas')) has-error @endif">
        {!! Form::label('mtc_horas_praticas', 'Horas Práticas', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::number('mtc_horas_praticas', old('mtc_horas_praticas'), ['class' => 'form-control select-control']) !!}
            @if ($errors->has('mtc_horas_praticas')) <p class="help-block">{{ $errors->first('mtc_horas_praticas') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-9 @if ($errors->has('mtc_descricao')) has-error @endif">
      {!! Form::label('mtc_descricao', 'Descrição', ['class' => 'control-label']) !!}
      <div class="controls">
        {!! Form::text('mtc_descricao', old('mtc_descricao'), ['class' => 'form-control select-control']) !!}
        @if ($errors->has('mtc_descricao')) <p class="help-block">{{ $errors->first('mtc_descricao') }}</p> @endif
      </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>
