<div class="row">
    <div class="form-group col-md-3 @if ($errors->has('perfis')) has-error @endif">
        {!! Form::label('perfis', 'Perfis*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('perfis[]', $perfis, old('perfis[]'), ['class' => 'form-control', 'multiple' => 'multiple']) !!}
            @if ($errors->has('perfis')) <p class="help-block">{{ $errors->first('perfis') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('itt_id')) has-error @endif">
        {!! Form::label('itt_id', 'Instituições*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('itt_id', $instituicoes, old('itt_id'), ['class' => 'form-control', 'placeholder' => 'Selecione a instituição']) !!}
            @if ($errors->has('itt_id')) <p class="help-block">{{ $errors->first('itt_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('doc_file')) has-error @endif">
        {!! Form::label('doc_file', 'Arquivo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::file('doc_file', ['class' => 'form-control file']) !!}
            @if ($errors->has('doc_file')) <p class="help-block">{{ $errors->first('doc_file') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>
