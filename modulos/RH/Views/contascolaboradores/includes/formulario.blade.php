<div class="row">
    <div class="form-group col-md-3 @if ($errors->has('ccb_ban_id')) has-error @endif">
        {!! Form::label('ccb_ban_id', 'Banco*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('ccb_ban_id', $bancos, old('ccb_ban_id'), ['class' => 'form-control', 'placeholder' => 'Selecione o banco']) !!}
            @if ($errors->has('ccb_ban_id')) <p class="help-block">{{ $errors->first('ccb_ban_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('ccb_agencia')) has-error @endif">
        {!! Form::label('ccb_agencia', 'Agência*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('ccb_agencia', old('ccb_agencia'), ['class' => 'form-control']) !!}
            @if ($errors->has('ccb_agencia')) <p class="help-block">{{ $errors->first('ccb_agencia') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('ccb_conta')) has-error @endif">
      {!! Form::label('ccb_conta', 'Conta*', ['class' => 'control-label']) !!}
      <div class="controls">
        {!! Form::text('ccb_conta', old('ccb_conta'), ['class' => 'form-control']) !!}
        @if ($errors->has('ccb_conta')) <p class="help-block">{{ $errors->first('ccb_conta') }}</p> @endif
      </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('ccb_variacao')) has-error @endif">
        {!! Form::label('ccb_variacao', 'Variação*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('ccb_variacao', old('ccb_variacao'), ['class' => 'form-control']) !!}
            @if ($errors->has('ccb_variacao')) <p class="help-block">{{ $errors->first('ccb_variacao') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12">
        <label class="control-label" style="visibility: hidden">Botão</label>
        <div class="controls">
            {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
        </div>
    </div>
</div>
