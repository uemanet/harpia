<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('ofc_id')) has-error @endif">
        {!! Form::label('ofc_id', 'Ano da Oferta', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('ofc_id', $oferta, null, ['disabled', 'class' => 'form-control', 'id' => 'ofc_id']) !!}
            @if ($errors->has('ofc_id')) <p class="help-block">{{ $errors->first('ofc_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('trm_id')) has-error @endif">
        {!! Form::label('trm_id', 'Turma', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('trm_id', $turma, null, ['disabled', 'class' => 'form-control', 'id' => 'trm_id']) !!}
            @if ($errors->has('trm_id')) <p class="help-block">{{ $errors->first('trm_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('ttg_grp_id')) has-error @endif">
        {!! Form::label('ttg_grp_id', 'Grupo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('ttg_grp_id', $grupo, $grupo, ['class' => 'form-control', 'id' => 'ttg_grp_id']) !!}
            @if ($errors->has('ttg_grp_id')) <p class="help-block">{{ $errors->first('ttg_grp_id') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('ttg_tut_id')) has-error @endif">
        {!! Form::label('ttg_tut_id', 'Tutor*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('ttg_tut_id', $tutores, $tutores, ['class' => 'form-control', 'id' => 'ttg_tut_id', 'placeholder' => 'Selecione o tutor']) !!}
            @if ($errors->has('ttg_tut_id')) <p class="help-block">{{ $errors->first('ttg_tut_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('ttg_tipo_tutoria')) has-error @endif">
        {!! Form::label('ttg_tipo_tutoria', 'Tipo de tutoria*', ['class' => 'control-label']) !!}
        <div class="controls">
            @if($presencial === true){!! Form::select('ttg_tipo_tutoria', array('distancia' => 'À Distância'), $tutores, ['class' => 'form-control', 'id' => 'ttg_tipo_tutoria', 'placeholder' => 'Selecione o tipo de tutoria']) !!}@endif
            @if($distancia === true){!! Form::select('ttg_tipo_tutoria', array('presencial' => 'Presencial'), $tutores, ['class' => 'form-control', 'id' => 'ttg_tipo_tutoria', 'placeholder' => 'Selecione o tipo de tutoria']) !!}@endif
            @if($presencial === null and $distancia==null){!! Form::select('ttg_tipo_tutoria', array('presencial' => 'Presencial', 'distancia' => 'À Distância'), $tutores, ['class' => 'form-control', 'id' => 'ttg_tipo_tutoria', 'placeholder' => 'Selecione o tipo de tutoria']) !!}@endif
            @if ($errors->has('ttg_tipo_tutoria')) <p class="help-block">{{ $errors->first('ttg_tipo_tutoria') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('ttg_data_inicio')) has-error @endif">
        {!! Form::label('ttg_data_inicio', 'Data de Admissão do tutor*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('ttg_data_inicio', old('ttg_data_inicio'), ['class' => 'form-control datepicker', 'data-provide' => 'datepicker', 'date-date-format' => 'dd/mm/yyyy']) !!}
            @if ($errors->has('ttg_data_inicio')) <p class="help-block">{{ $errors->first('ttg_data_inicio') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>
