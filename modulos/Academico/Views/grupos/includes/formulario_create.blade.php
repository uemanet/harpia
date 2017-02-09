<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('crs_id')) has-error @endif">
        {!! Form::label('crs_id', 'Curso*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('crs_id', $curso, old('crs_id'), ['disabled', 'class' => 'form-control select-control']) !!}
            @if ($errors->has('crs_id')) <p class="help-block">{{ $errors->first('crs_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if($errors->has('ofc_id')) has-error @endif">
        {!! Form::label('ofc_id', 'Oferta de Curso*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('ofc_id', $oferta, old('ofc_id'), ['disabled', 'class' => 'form-control select-control']) !!}
            @if ($errors->has('ofc_id')) <p class="help-block">{{ $errors->first('crs_id')}} </p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('grp_trm_id')) has-error @endif">
        {!! Form::label('grp_trm_id', 'Turma*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('grp_trm_id', $turma, old('grp_trm_id'), ['class' => 'form-control']) !!}
            @if ($errors->has('grp_trm_id')) <p class="help-block">{{ $errors->first('grp_trm_id') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('grp_pol_id')) has-error @endif">
        {!! Form::label('grp_pol_id', 'Polo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('grp_pol_id', $polos, old('grp_pol_id'), ['class' => 'form-control', 'placeholder' => 'Selecione o polo']) !!}
            @if ($errors->has('grp_pol_id')) <p class="help-block">{{ $errors->first('grp_pol_id') }}</p> @endif
        </div>
    </div>
<!-- </div> -->

@include('Academico::grupos.includes.formulario_base')
