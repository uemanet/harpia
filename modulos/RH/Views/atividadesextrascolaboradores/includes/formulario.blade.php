<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('atc_titulo')) has-error @endif">
        {!! Form::label('atc_titulo', 'Título*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('atc_titulo', old('atc_titulo'), ['class' => 'form-control']) !!}
            @if ($errors->has('atc_titulo')) <p class="help-block">{{ $errors->first('atc_titulo') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('atc_descricao')) has-error @endif">
      {!! Form::label('atc_descricao', 'Descrição', ['class' => 'control-label']) !!}
      <div class="controls">
        {!! Form::text('atc_descricao', old('atc_descricao'), ['class' => 'form-control']) !!}
        @if ($errors->has('atc_descricao')) <p class="help-block">{{ $errors->first('atc_descricao') }}</p> @endif
      </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('atc_tipo')) has-error @endif">
        {!! Form::label('atc_tipo', 'Tipo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('atc_tipo', array('curso' => 'curso', 'evento' => 'evento', 'oficina' => 'oficina'), old('atc_tipo'), ['class' => 'form-control', 'placeholder' => 'Selecione']) !!}
            @if ($errors->has('atc_tipo')) <p class="help-block">{{ $errors->first('atc_tipo') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('atc_carga_horaria')) has-error @endif">
        {!! Form::label('atc_carga_horaria', 'Carga Horária', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::number('atc_carga_horaria', old('atc_carga_horaria'), ['min' => 1, 'max' => 9999, 'maxlength' => 4 ,'class' => 'form-control',  'oninput'=>"javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"]) !!}
            @if ($errors->has('atc_carga_horaria')) <p class="help-block">{{ $errors->first('atc_carga_horaria') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('atc_data_inicio')) has-error @endif">
        {!! Form::label('atc_data_inicio', 'Data de Início', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('atc_data_inicio',old('atc_data_inicio'), ['class' => 'form-control datepicker', 'data-provide' => 'datepicker', 'date-date-format' => 'dd/mm/yyyy']) !!}
            @if ($errors->has('atc_data_inicio')) <p
                    class="help-block">{{ $errors->first('atc_data_inicio') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('atc_data_fim')) has-error @endif">
        {!! Form::label('atc_data_fim', 'Data de Conclusão', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('atc_data_fim',old('atc_data_fim'), ['class' => 'form-control datepicker', 'data-provide' => 'datepicker', 'date-date-format' => 'dd/mm/yyyy']) !!}
            @if ($errors->has('atc_data_fim')) <p
                    class="help-block">{{ $errors->first('atc_data_fim') }}</p> @endif
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
