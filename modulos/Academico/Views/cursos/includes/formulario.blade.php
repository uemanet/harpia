<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('crs_dep_id')) has-error @endif">
        {!! Form::label('crs_dep_id', 'Departamento*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('crs_dep_id', $departamentos, old('crs_dep_id'), ['placeholder' => 'Selecione um departamento','class' => 'form-control']) !!}
            @if ($errors->has('crs_dep_id')) <p class="help-block">{{ $errors->first('crs_dep_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('crs_nvc_id')) has-error @endif">
        {!! Form::label('crs_nvc_id', 'Nível do Curso*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('crs_nvc_id', $niveiscursos, old('crs_nvc_id'), ['placeholder' => 'Selecione um nível','class' => 'form-control']) !!}
            @if ($errors->has('crs_nvc_id')) <p class="help-block">{{ $errors->first('crs_nvc_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('crs_prf_diretor')) has-error @endif">
        {!! Form::label('crs_prf_diretor', 'Diretor do curso*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('crs_prf_diretor', $professores, old('crs_prf_diretor'), ['placeholder' => 'Selecione um diretor','class' => 'form-control']) !!}
            @if ($errors->has('crs_prf_diretor')) <p class="help-block">{{ $errors->first('crs_prf_diretor') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-8 @if ($errors->has('crs_nome')) has-error @endif">
        {!! Form::label('crs_nome', 'Nome do curso*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('crs_nome', old('crs_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('crs_nome')) <p class="help-block">{{ $errors->first('crs_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 @if ($errors->has('crs_sigla')) has-error @endif">
        {!! Form::label('crs_sigla', 'Sigla*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('crs_sigla', old('crs_sigla'), ['class' => 'form-control']) !!}
            @if ($errors->has('crs_sigla')) <p class="help-block">{{ $errors->first('crs_sigla') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 @if ($errors->has('crs_data_autorizacao')) has-error @endif">
        {!! Form::label('crs_data_autorizacao', 'Data de autorização*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('crs_data_autorizacao', old('crs_data_autorizacao'), ['class' => 'form-control datepicker', 'data-provide' => 'datepicker', 'date-date-format' => 'dd/mm/yyyy']) !!}
            @if ($errors->has('crs_data_autorizacao')) <p class="help-block">{{ $errors->first('crs_data_autorizacao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('crs_descricao')) has-error @endif">
        {!! Form::label('crs_descricao', 'Descrição*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::textarea('crs_descricao', old('crs_descricao'), ['class' => 'form-control', 'rows' => '4']) !!}
            @if ($errors->has('crs_descricao')) <p class="help-block">{{ $errors->first('crs_descricao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('crs_resolucao')) has-error @endif">
        {!! Form::label('crs_resolucao', 'Resolução*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('crs_resolucao', old('crs_resolucao'), ['class' => 'form-control']) !!}
            @if ($errors->has('crs_resolucao')) <p class="help-block">{{ $errors->first('crs_resolucao') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('crs_autorizacao')) has-error @endif">
        {!! Form::label('crs_autorizacao', 'Autorização*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('crs_autorizacao', old('crs_autorizacao'), ['class' => 'form-control']) !!}
            @if ($errors->has('crs_autorizacao')) <p class="help-block">{{ $errors->first('crs_autorizacao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('crs_eixo')) has-error @endif">
        {!! Form::label('crs_eixo', 'Eixo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('crs_eixo', old('crs_eixo'), ['class' => 'form-control']) !!}
            @if ($errors->has('crs_eixo')) <p class="help-block">{{ $errors->first('crs_eixo') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('crs_habilitacao')) has-error @endif">
        {!! Form::label('crs_habilitacao', 'Habilitação*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('crs_habilitacao', old('crs_habilitacao'), ['class' => 'form-control']) !!}
            @if ($errors->has('crs_habilitacao')) <p class="help-block">{{ $errors->first('crs_habilitacao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>
