<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('dis_nome')) has-error @endif">
        {!! Form::label('dis_nome', 'Nome da disciplina*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('dis_nome', old('dis_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('dis_nome')) <p class="help-block">{{ $errors->first('dis_nome') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('dis_carga_horaria')) has-error @endif">
        {!! Form::label('dis_carga_horaria', 'Carga-Horária*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::number('dis_carga_horaria', old('dis_carga_horaria'), ['class' => 'form-control']) !!}
            @if ($errors->has('dis_carga_horaria')) <p
                    class="help-block">{{ $errors->first('dis_carga_horaria') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('dis_creditos')) has-error @endif">
        {!! Form::label('dis_creditos', 'Créditos*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::number('dis_creditos', old('dis_creditos'), ['class' => 'form-control']) !!}
            @if ($errors->has('dis_creditos')) <p class="help-block">{{ $errors->first('dis_creditos') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('dis_nvc_id')) has-error @endif">
        {!! Form::label('dis_nvc_id', 'Nivel*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('dis_nvc_id', $niveis, old('dis_nvc_id'), ['class' => 'form-control']) !!}
            @if ($errors->has('dis_nvc_id')) <p class="help-block">{{ $errors->first('dis_nvc_id') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('dis_ementa')) has-error @endif">
        {!! Form::label('dis_ementa', 'Ementa', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::textarea('dis_ementa', old('dis_ementa'), ['class' => 'form-control']) !!}
            @if ($errors->has('dis_ementa')) <p class="help-block">{{ $errors->first('dis_ementa') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('dis_bibliografia')) has-error @endif">
        {!! Form::label('dis_bibliografia', 'Bibliografia', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::textarea('dis_bibliografia', old('dis_bibliografia'), ['class' => 'form-control']) !!}
            @if ($errors->has('dis_bibliografia')) <p
                    class="help-block">{{ $errors->first('dis_bibliografia') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>