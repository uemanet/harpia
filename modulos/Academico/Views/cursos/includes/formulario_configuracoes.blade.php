<div class="row">
    <div class="col-md-12">
        <h4>Configurações do Curso</h4>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-4 form-group @if ($errors->has('media_min_aprovacao')) has-error @endif">
        {!! Form::label('media_min_aprovacao', 'Média Mínima Para Aprovação*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::number('media_min_aprovacao', old('media_min_aprovacao'), ['class' => 'form-control',
            'min' => '1.0', 'max' => '10.0', 'step' => '0.1']) !!}
            @if ($errors->has('media_min_aprovacao')) <p class="help-block">{{ $errors->first('media_min_aprovacao') }}</p> @endif
        </div>
    </div>
    <div class="col-md-4 form-group @if ($errors->has('media_min_final')) has-error @endif">
        {!! Form::label('media_min_final', 'Média Mínima para ir à Prova Final*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::number('media_min_final', old('media_min_final'), ['class' => 'form-control',
            'min' => '1.0', 'max' => '10.0', 'step' => '0.1']) !!}
            @if ($errors->has('media_min_final')) <p class="help-block">{{ $errors->first('media_min_final') }}</p> @endif
        </div>
    </div>
    <div class="col-md-4 form-group @if ($errors->has('media_min_aprovacao_final')) has-error @endif">
        {!! Form::label('media_min_aprovacao_final', 'Média Mínima para aprovação na Prova Final*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::number('media_min_aprovacao_final', old('media_min_aprovacao_final'), ['class' => 'form-control',
            'min' => '1.0', 'max' => '10.0', 'step' => '0.1']) !!}
            @if ($errors->has('media_min_aprovacao_final')) <p class="help-block">{{ $errors->first('media_min_aprovacao_final') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 form-group @if ($errors->has('modo_recuperacao')) has-error @endif">
        {!! Form::label('modo_recuperacao', 'Modo de Recuperação*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('modo_recuperacao', ['substituir_menor_nota' => 'Substituir Menor Nota',
            'substituir_media_final' => 'Substituir Média Final'],old('modo_recuperacao') ,
            ['class' => 'form-control', 'placeholder' => 'Selecione o modo']) !!}
            @if ($errors->has('modo_recuperacao')) <p class="help-block">{{ $errors->first('modo_recuperacao') }}</p> @endif
        </div>
    </div>
    <div class="col-md-6 form-group @if ($errors->has('conceitos_aprovacao')) has-error @endif">
        {!! Form::label('conceitos_aprovacao', 'Conceitos para Aprovação*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('conceitos_aprovacao', ['Insuficiente' => 'Insuficiente',
            'Regular' => 'Regular', 'Bom' => 'Bom', 'Muito Bom' => 'Muito Bom', 'Excelente' => 'Excelente'],
            old('conceitos_aprovacao'), ['class' => 'form-control', 'multiple' => 'multiple']) !!}
            @if ($errors->has('conceitos_aprovacao')) <p class="help-block">{{ $errors->first('conceitos_aprovacao') }}</p> @endif
        </div>
    </div>
</div>