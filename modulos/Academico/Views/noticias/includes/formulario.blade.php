<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('ntc_titulo')) has-error @endif">
        {!! Form::label('ntc_titulo', 'Título da Notícia*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('ntc_titulo', old('ntc_titulo'), ['class' => 'form-control', 'required' => 'required']) !!}
            @if ($errors->has('ntc_titulo')) <p class="help-block">{{ $errors->first('ntc_titulo') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('ntc_descricao')) has-error @endif">
        {!! Form::label('ntc_descricao', 'Descrição*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::textarea('ntc_descricao', old('ntc_descricao'), ['class' => 'form-control', 'required' => 'required']) !!}
            @if ($errors->has('ntc_descricao')) <p class="help-block">{{ $errors->first('ntc_descricao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>