<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('amb_nome')) has-error @endif">
        {!! Form::label('amb_nome', 'Nome do ambiente*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('amb_nome', old('amb_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('amb_nome')) <p class="help-block">{{ $errors->first('amb_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('amb_versao')) has-error @endif">
        {!! Form::label('amb_versao', 'Versão*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('amb_versao', old('amb_versao'), ['class' => 'form-control']) !!}
            @if ($errors->has('amb_versao')) <p class="help-block">{{ $errors->first('amb_versao') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-12 @if ($errors->has('amb_url')) has-error @endif">
        {!! Form::label('amb_url', 'Url*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('amb_url', old('amb_url'), ['class' => 'form-control']) !!}
            @if ($errors->has('amb_url')) <p class="help-block">{{ $errors->first('amb_url') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>
