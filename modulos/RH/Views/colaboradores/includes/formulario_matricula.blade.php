<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('mtc_data_inicio')) has-error @endif">
        {!! Form::label('mtc_data_inicio', 'Data de admissão*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('mtc_data_inicio',isset($colaborador->mtc_data_inicio) ? $colaborador->mtc_data_inicio : old('mtc_data_inicio'), ['class' => 'form-control datepicker', 'data-provide' => 'datepicker', 'date-date-format' => 'dd/mm/yyyy']) !!}
            @if ($errors->has('mtc_data_inicio')) <p
                    class="help-block">{{ $errors->first('mtc_data_inicio') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <label class="control-label" style="visibility: hidden">Botão</label>
        <div class="controls">
            {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-left']) !!}
        </div>
    </div>
</div>

