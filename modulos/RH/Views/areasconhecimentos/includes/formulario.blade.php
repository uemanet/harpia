<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('arc_descricao')) has-error @endif">
        {!! Form::label('arc_descricao', 'Nome da área de conhecimento*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('arc_descricao', old('arc_descricao'), ['class' => 'form-control']) !!}
            @if ($errors->has('arc_descricao')) <p class="help-block">{{ $errors->first('arc_descricao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>