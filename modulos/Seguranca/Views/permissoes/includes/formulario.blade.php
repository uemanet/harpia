<div class="row">
    <div class="form-group col-md-3 @if ($errors->has(',mod_id')) has-error @endif">
        {!! Form::label(',mod_id', 'Módulo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select(',mod_id', $modulos, old(',mod_id'), ['class' => 'form-control']) !!}
            @if ($errors->has(',mod_id')) <p class="help-block">{{ $errors->first(',mod_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-9 @if ($errors->has('prm_nome')) has-error @endif">
        {!! Form::label('prm_nome', 'Nome da permissão*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('prm_nome', old('prm_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('prm_nome')) <p class="help-block">{{ $errors->first('prm_nome') }}</p> @endif
        </div>
    </div>
</div>
<div class="form-group @if ($errors->has('prm_descricao')) has-error @endif">
    {!! Form::label('prm_descricao', 'Descrição da permissão*', ['class' => 'control-label']) !!}
    <div class="controls">
        {!! Form::text('prm_descricao', old('prm_descricao'), ['class' => 'form-control']) !!}
        @if ($errors->has('prm_descricao')) <p class="help-block">{{ $errors->first('prm_descricao') }}</p> @endif
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>