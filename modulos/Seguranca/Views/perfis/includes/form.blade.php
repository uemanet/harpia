<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('prf_mod_id')) has-error @endif">
        {!! Form::label('prf_mod_id', 'Modulos', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('prf_mod_id', $modulos, old('prf_mod_id'), ['class'=>'form-control coletanea', 'aria-required'=>'true']) !!}
            @if ($errors->has('prf_mod_id')) <p class="help-block">{{ $errors->first('prf_mod_id') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6 col-sm-4 col-xs-6 @if ($errors->has('prf_nome')) has-error @endif">
        {!! Form::label('prf_nome', 'Nome*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('prf_nome', old('prf_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('prf_nome')) <p class="help-block">{{ $errors->first('prf_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 col-xs-6 @if ($errors->has('prf_descricao')) has-error @endif">
        {!! Form::label('prf_descricao', 'Descricao*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('prf_descricao', old('prf_descricao'), ['class' => 'form-control']) !!}
            @if ($errors->has('prf_descricao')) <p class="help-block">{{ $errors->first('prf_descricao') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>
