<div class="row">
  <div class="form-group col-md-4 @if ($errors->has('crs_id')) has-error @endif">
      {!! Form::label('crs_id', 'Curso*', ['class' => 'control-label']) !!}
      <div class="controls">
          {!! Form::select('crs_id', $cursos, old('crs_id'), ['class' => 'form-control', 'placeholder' => 'Selecione um curso', 'id' => 'crs_id']) !!}
          @if ($errors->has('crs_id')) <p class="help-block">{{ $errors->first('crs_id') }}</p> @endif
      </div>
  </div>
    <div class="form-group col-md-4 @if ($errors->has('trm_ofc_id')) has-error @endif">
        {!! Form::label('trm_ofc_id', 'Oferta de Curso*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('trm_ofc_id', $ofertascursos, old('trm_ofc_id'), ['class' => 'form-control', 'id' => 'trm_ofc_id']) !!}
            @if ($errors->has('trm_ofc_id')) <p class="help-block">{{ $errors->first('trm_ofc_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('trm_per_id')) has-error @endif">
      {!! Form::label('trm_per_id', 'Período Letivo*', ['class' => 'control-label']) !!}
      <div class="controls">
        {!! Form::select('trm_per_id', $periodosletivos, old('trm_per_id'), ['class' => 'form-control']) !!}
        @if ($errors->has('trm_per_id')) <p class="help-block">{{ $errors->first('trm_per_id') }}</p> @endif
      </div>
    </div>
</div>

@include('Academico::turmas.includes.formulario_base')
