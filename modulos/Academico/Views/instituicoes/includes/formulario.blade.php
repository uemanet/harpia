<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('itt_nome')) has-error @endif">
        {!! Form::label('itt_nome', 'Nome da instituição*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('itt_nome', old('itt_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('itt_nome')) <p class="help-block">{{ $errors->first('itt_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 @if ($errors->has('itt_sigla')) has-error @endif">
        {!! Form::label('itt_sigla', 'Sigla*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('itt_sigla', old('itt_sigla'), ['class' => 'form-control']) !!}
            @if ($errors->has('itt_sigla')) <p class="help-block">{{ $errors->first('itt_sigla') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-8">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>