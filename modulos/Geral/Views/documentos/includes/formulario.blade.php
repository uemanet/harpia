<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('doc_tpd_id')) has-error @endif">
        {!! Form::label('doc_tpd_id', 'Tipo de Documento*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('doc_tpd_id', $tiposdocumentos, old('doc_tpd_id'), ['placeholder' => 'Selecione um documento','class' => 'form-control']) !!}
            @if ($errors->has('doc_tpd_id')) <p class="help-block">{{ $errors->first('doc_tpd_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('doc_conteudo')) has-error @endif">
      {!! Form::label('doc_conteudo', 'Conteúdo', ['class' => 'control-label']) !!}
      <div class="controls">
        {!! Form::text('doc_conteudo', old('doc_conteudo'), ['class' => 'form-control']) !!}
        @if ($errors->has('doc_conteudo')) <p class="help-block">{{ $errors->first('doc_conteudo') }}</p> @endif
      </div>
    </div>
    <div class="form-group col-md-2 @if ($errors->has('doc_orgao')) has-error @endif">
        {!! Form::label('doc_orgao', 'Órgão', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('doc_orgao', old('doc_orgao'), ['class' => 'form-control']) !!}
            @if ($errors->has('doc_orgao')) <p class="help-block">{{ $errors->first('doc_orgao') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 @if ($errors->has('doc_data_expedicao')) has-error @endif">
        {!! Form::label('doc_data_expedicao', 'Data de expedição*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('doc_data_expedicao', old('doc_data_expedicao'), ['class' => 'form-control datepicker', 'data-provide' => 'datepicker', 'date-date-format' => 'dd/mm/yyyy']) !!}
            @if ($errors->has('doc_data_expedicao')) <p class="help-block">{{ $errors->first('doc_data_expedicao') }}</p> @endif
        </div>
    </div>
</div>
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
    <div class="form-group col-md-12 @if ($errors->has('doc_observacao')) has-error @endif">
        {!! Form::label('doc_observacao', 'Observação', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::textarea('doc_observacao', old('doc_observacao'), ['class' => 'form-control', 'rows' => '4']) !!}
            @if ($errors->has('doc_observacao')) <p class="help-block">{{ $errors->first('doc_observacao') }}</p> @endif
        </div>
    </div>
</div>
{!! Form::input('hidden' , 'doc_pes_id', $pessoa->pes_id ,  ['class' => 'form-control']) !!}

<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>
