<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('pol_nome')) has-error @endif">
        {!! Form::label('pol_nome', 'Nome do polo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pol_nome', old('pol_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('pol_nome')) <p class="help-block">{{ $errors->first('pol_nome') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>