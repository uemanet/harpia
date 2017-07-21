<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('dep_cen_id')) has-error @endif">
        {!! Form::label('dep_cen_id', 'Centro*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('dep_cen_id', $centros, old('dep_cen_id'), ['class' => 'form-control select-control', 'placeholder' => 'Selecione o centro']) !!}
            @if ($errors->has('dep_cen_id')) <p class="help-block">{{ $errors->first('dep_cen_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('dep_prf_diretor')) has-error @endif">
        {!! Form::label('dep_prf_diretor', 'Diretor do departamento*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('dep_prf_diretor', $professores, old('dep_prf_diretor'), ['class' => 'form-control', 'placeholder' => 'Selecione o diretor']) !!}
            @if ($errors->has('dep_prf_diretor')) <p class="help-block">{{ $errors->first('dep_prf_diretor') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('dep_nome')) has-error @endif">
        {!! Form::label('dep_nome', 'Nome do departamento*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('dep_nome', old('dep_nome'), ['class' => 'form-control select-control']) !!}
            @if ($errors->has('dep_nome')) <p class="help-block">{{ $errors->first('dep_nome') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>
