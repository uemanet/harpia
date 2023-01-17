<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('not_titulo')) has-error @endif">
        {!! Form::label('not_titulo', 'Título da Notícia*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('not_titulo', old('not_titulo'), ['class' => 'form-control', 'required' => 'required']) !!}
            @if ($errors->has('not_titulo')) <p class="help-block">{{ $errors->first('not_titulo') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('not_link')) has-error @endif">
        {!! Form::label('not_link', 'Link*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('not_link', old('not_link'), ['class' => 'form-control', 'required' => 'required']) !!}
            @if ($errors->has('not_link')) <p class="help-block">{{ $errors->first('not_link') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('not_descricao')) has-error @endif">
        {!! Form::label('not_descricao', 'Descrição*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::textarea('not_descricao', old('not_descricao'), ['class' => 'form-control', 'required' => 'required']) !!}
            @if ($errors->has('not_descricao')) <p class="help-block">{{ $errors->first('not_descricao') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('not_corpo')) has-error @endif">
        {!! Form::label('not_corpo', 'Corpo', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::textarea('not_corpo', old('not_corpo'), ['class' => 'form-control']) !!}
            @if ($errors->has('not_corpo')) <p
                    class="help-block">{{ $errors->first('not_corpo') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>