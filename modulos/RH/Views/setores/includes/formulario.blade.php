<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('set_descricao')) has-error @endif">
        {!! Form::label('set_descricao', 'Descrição*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('set_descricao', old('set_descricao'), ['class' => 'form-control']) !!}
            @if ($errors->has('set_descricao')) <p class="help-block">{{ $errors->first('set_descricao') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('set_sigla')) has-error @endif">
        {!! Form::label('set_sigla', 'Sigla*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('set_sigla', old('set_sigla'), ['class' => 'form-control']) !!}
            @if ($errors->has('set_sigla')) <p class="help-block">{{ $errors->first('set_sigla') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>