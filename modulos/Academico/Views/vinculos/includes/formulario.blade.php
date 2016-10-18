<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('cursos')) has-error @endif">
        {!! Form::label('cursos', 'Cursos*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('cursos[]', $cursos, old('cursos[]'), ['class' => 'form-control', 'multiple' => 'multiple']) !!}
            @if ($errors->has('cursos')) <p class="help-block">{{ $errors->first('cursos') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>
