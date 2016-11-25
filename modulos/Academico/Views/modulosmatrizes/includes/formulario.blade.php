<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('crs_id')) has-error @endif">
        {!! Form::label('crs_id', 'Curso*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('crs_id', $curso, null, ['disabled', 'class' => 'form-control', 'id' => 'crs_id']) !!}
            @if ($errors->has('crs_id')) <p class="help-block">{{ $errors->first('crs_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('mdo_mtc_id')) has-error @endif">
        {!! Form::label('mdo_mtc_id', 'Matriz*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('mdo_mtc_id', $matriz, $matriz, ['class' => 'form-control', 'id' => 'mdo_mtc_id']) !!}
            @if ($errors->has('mdo_mtc_id')) <p class="help-block">{{ $errors->first('mdo_mtc_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('mdo_nome')) has-error @endif">
        {!! Form::label('mdo_nome', 'Nome*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('mdo_nome', old('mdo_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('mdo_nome')) <p class="help-block">{{ $errors->first('mdo_nome') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('mdo_descricao')) has-error @endif">
        {!! Form::label('mdo_descricao', 'Descrição', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::textarea('mdo_descricao', old('mdo_descricao'), ['class' => 'form-control']) !!}
            @if ($errors->has('mdo_descricao')) <p class="help-block">{{ $errors->first('mdo_descricao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('mdo_qualificacao')) has-error @endif">
        {!! Form::label('mdo_qualificacao', 'Qualificação', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('mdo_qualificacao', old('mdo_qualificacao'), ['class' => 'form-control']) !!}
            @if ($errors->has('mdo_qualificacao')) <p class="help-block">{{ $errors->first('mdo_qualificacao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>
