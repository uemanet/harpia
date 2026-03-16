<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('prm_nome')) has-error @endif">
        {!! Form::label('prm_nome', 'Nome*', ['class' => 'form-label']) !!}
        <div class="controls">
            {!! Form::text('prm_nome', old('prm_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('prm_nome')) <p class="help-block">{{ $errors->first('prm_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('prm_rota')) has-error @endif">
        {!! Form::label('prm_rota', 'Rota*', ['class' => 'form-label']) !!}
        <div class="controls">
            {!! Form::text('prm_rota', old('prm_rota'), ['class' => 'form-control']) !!}
            @if ($errors->has('prm_rota')) <p class="help-block">{{ $errors->first('prm_rota') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('prm_descricao')) has-error @endif">
        {!! Form::label('prm_descricao', 'Descrição', ['class' => 'form-label']) !!}
        <div class="controls">
            {!! Form::text('prm_descricao', old('prm_descricao'), ['class' => 'form-control']) !!}
            @if ($errors->has('prm_descricao')) <p class="help-block">{{ $errors->first('prm_descricao') }}</p> @endif
        </div>
    </div>
</div>