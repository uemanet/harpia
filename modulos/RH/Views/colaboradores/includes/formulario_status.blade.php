<div class="row">
    <div class="form-group col-md-2 @if ($errors->has('col_status')) has-error @endif">
        {!! Form::label('col_status', 'Status*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('col_status', array('ativo' => 'ativo', 'afastado' => 'afastado', 'desligado' => 'desligado'), isset($colaborador->col_status) ? $colaborador->col_status : old('col_status'), ['class' => 'form-control', 'placeholder' => 'Selecione']) !!}
            @if ($errors->has('col_status')) <p class="help-block">{{ $errors->first('col_status') }}</p> @endif
        </div>
    </div>
</div>
