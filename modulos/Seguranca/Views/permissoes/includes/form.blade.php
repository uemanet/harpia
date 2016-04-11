<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('prm_rcs_id')) has-error @endif">
        {!! Form::label('prm_rcs_id', 'Recurso', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('prm_rcs_id', $recursos, old('prm_rcs_id'), ['class'=>'form-control coletanea', 'aria-required'=>'true']) !!}
            @if ($errors->has('prm_rcs_id')) <p class="help-block">{{ $errors->first('prm_rcs_id') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('prm_nome')) has-error @endif">
        {!! Form::label('prm_nome', 'Permissão', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('prm_nome', old('prm_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('prm_nome')) <p class="help-block">{{ $errors->first('prm_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('prm_descricao')) has-error @endif">
        {!! Form::label('prm_descricao', 'Descrição', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('prm_descricao', old('prm_descricao'), ['class' => 'form-control']) !!}
            @if ($errors->has('prm_descricao')) <p class="help-block">{{ $errors->first('prm_descricao') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>