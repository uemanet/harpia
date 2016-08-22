<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('per_inicio')) has-error @endif">
        {!! Form::label('per_inicio', 'Data de Início*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::date('per_inicio', old('per_inicio'), ['class' => 'form-control', 'min' => '01-01-2016']) !!}
            @if ($errors->has('per_inicio')) <p class="help-block">{{ $errors->first('per_inicio') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('per_fim')) has-error @endif">
        {!! Form::label('per_fim', 'Data de Encerramento*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::date('per_fim', old('per_fim'), ['class' => 'form-control', 'placeholder' => '12-31-2016']) !!}
            @if ($errors->has('per_fim')) <p class="help-block">{{ $errors->first('per_fim') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>