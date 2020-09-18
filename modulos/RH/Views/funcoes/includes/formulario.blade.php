<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('fun_descricao')) has-error @endif">
        {!! Form::label('fun_descricao', 'Função*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('fun_descricao', old('fun_descricao'), ['class' => 'form-control']) !!}
            @if ($errors->has('fun_descricao')) <p class="help-block">{{ $errors->first('fun_descricao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>