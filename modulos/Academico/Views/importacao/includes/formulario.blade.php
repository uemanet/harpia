<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('doc_file')) has-error @endif">
        {!! Form::label('doc_file', 'Documento', ['class' => 'control-label']) !!}
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