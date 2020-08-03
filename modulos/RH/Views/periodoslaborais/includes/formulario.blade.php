<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('pel_inicio')) has-error @endif">
        {!! Form::label('pel_inicio', 'Início*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pel_inicio', old('pel_inicio'), ['class' => 'form-control datepicker', 'data-provide' => 'datepicker', 'date-date-format' => 'dd/mm/yyyy']) !!}
            @if ($errors->has('pel_inicio')) <p class="help-block">{{ $errors->first('pel_inicio') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('pel_termino')) has-error @endif">
        {!! Form::label('pel_termino', 'Término*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pel_termino', old('pel_termino'), ['class' => 'form-control datepicker', 'data-provide' => 'datepicker', 'date-date-format' => 'dd/mm/yyyy']) !!}
            @if ($errors->has('pel_termino')) <p class="help-block">{{ $errors->first('pel_termino') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>