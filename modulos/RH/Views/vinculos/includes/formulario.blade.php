<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('vin_descricao')) has-error @endif">
        {!! Form::label('vin_descricao', 'Descrição*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('vin_descricao', old('vin_descricao'), ['class' => 'form-control']) !!}
            @if ($errors->has('vin_descricao')) <p class="help-block">{{ $errors->first('vin_descricao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>