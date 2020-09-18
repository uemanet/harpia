<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('vfp_vin_id')) has-error @endif">
        {!! Form::label('vfp_vin_id', 'Vínculo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('vfp_vin_id', $vinculos, old('vfp_vin_id'), ['class' => 'form-control', 'placeholder' => 'Selecione o tipo de vínculo']) !!}
            @if ($errors->has('vfp_vin_id')) <p class="help-block">{{ $errors->first('vfp_vin_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('vfp_unidade')) has-error @endif">
        {!! Form::label('vfp_unidade', 'Pagamento por unidade*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('vfp_unidade', array(0 => 'Não', 1 => 'Sim'), old('vfp_unidade'), ['class' => 'form-control', 'placeholder' => 'Selecione']) !!}
            @if ($errors->has('vfp_unidade')) <p class="help-block">{{ $errors->first('vfp_unidade') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('vfp_valor')) has-error @endif">
        {!! Form::label('vfp_valor', 'Valor (R$)*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('vfp_valor', old('vfp_valor'), ['class' => 'form-control', "onkeyup" =>"k(this);"]) !!}
            @if ($errors->has('vfp_valor')) <p class="help-block">{{ $errors->first('vfp_valor') }}</p> @endif
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
