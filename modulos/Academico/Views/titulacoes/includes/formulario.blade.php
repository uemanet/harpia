<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('tit_nome')) has-error @endif">
        {!! Form::label('tit_nome', 'Nome da titulação*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('tit_nome', old('tit_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('tit_nome')) <p class="help-block">{{ $errors->first('tit_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('tit_peso')) has-error @endif">
        {!! Form::label('tit_peso', 'Peso*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::number('tit_peso', old('tit_peso'), ['class' => 'form-control']) !!}
            @if ($errors->has('tit_peso')) <p class="help-block">{{ $errors->first('tit_peso') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('tit_descricao')) has-error @endif">
        {!! Form::label('tit_descricao', 'Descrição', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::textarea('tit_descricao', old('tit_descricao'), ['class' => 'form-control']) !!}
            @if ($errors->has('tit_descricao')) <p class="help-block">{{ $errors->first('tit_descricao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>