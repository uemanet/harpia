<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('itt_nome')) has-error @endif">
        {!! Form::label('itt_nome', 'Nome da instituição*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('itt_nome', old('itt_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('itt_nome')) <p class="help-block">{{ $errors->first('itt_nome') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>