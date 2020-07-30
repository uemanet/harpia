<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('ban_nome')) has-error @endif">
        {!! Form::label('ban_nome', 'Nome do banco*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('ban_nome', old('ban_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('ban_nome')) <p class="help-block">{{ $errors->first('ban_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('ban_sigla')) has-error @endif">
        {!! Form::label('ban_sigla', 'Sigla*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('ban_sigla', old('ban_sigla'), ['class' => 'form-control']) !!}
            @if ($errors->has('ban_sigla')) <p class="help-block">{{ $errors->first('ban_sigla') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('ban_codigo')) has-error @endif">
        {!! Form::label('ban_codigo', 'Código*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('ban_codigo', old('ban_codigo'), ['class' => 'form-control']) !!}
            @if ($errors->has('ban_codigo')) <p class="help-block">{{ $errors->first('ban_codigo') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>