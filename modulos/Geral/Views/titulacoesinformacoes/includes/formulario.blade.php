<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('tin_tit_id')) has-error @endif">
        {!! Form::label('tin_tit_id', 'Titulações*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('tin_tit_id', $titulacoes, old('tin_tit_id'), ['class' => 'form-control', 'placeholder' => 'Selecione uma titulação']) !!}
            @if ($errors->has('tin_tit_id')) <p class="help-block">{{ $errors->first('tin_tit_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('tin_titulo')) has-error @endif">
        {!! Form::label('tin_titulo', 'Título/Curso*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('tin_titulo', old('tin_titulo'), ['class' => 'form-control']) !!}
            @if ($errors->has('tin_titulo')) <p class="help-block">{{ $errors->first('tin_titulo') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('tin_instituicao')) has-error @endif">
      {!! Form::label('tin_instituicao', 'Instituição*', ['class' => 'control-label']) !!}
      <div class="controls">
        {!! Form::text('tin_instituicao', old('tin_instituicao'), ['class' => 'form-control']) !!}
        @if ($errors->has('tin_instituicao')) <p class="help-block">{{ $errors->first('tin_instituicao') }}</p> @endif
      </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('tin_instituicao_sigla')) has-error @endif">
        {!! Form::label('tin_instituicao_sigla', 'Instituição Sigla', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('tin_instituicao_sigla', old('tin_instituicao_sigla'), ['class' => 'form-control']) !!}
            @if ($errors->has('tin_instituicao_sigla')) <p class="help-block">{{ $errors->first('tin_instituicao_sigla') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('tin_instituicao_sede')) has-error @endif">
        {!! Form::label('tin_instituicao_sede', 'Instituição Sede*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('tin_instituicao_sede', old('tin_instituicao_sede'), ['class' => 'form-control']) !!}
            @if ($errors->has('tin_instituicao_sede')) <p class="help-block">{{ $errors->first('tin_instituicao_sede') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 @if ($errors->has('tin_anoinicio')) has-error @endif">
        {!! Form::label('tin_anoinicio', 'Ano Inicio*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::number('tin_anoinicio', old('tin_anoinicio'), ['min' => 1, 'max' => 9999, 'maxlength' => 4 ,'class' => 'form-control',  'oninput'=>"javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"]) !!}
            @if ($errors->has('tin_anoinicio')) <p class="help-block">{{ $errors->first('tin_anoinicio') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 @if ($errors->has('tin_anofim')) has-error @endif">
        {!! Form::label('tin_anofim', 'Ano Fim', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::number('tin_anofim', old('tin_anofim'), ['min' => 1, 'max' => 9999, 'maxlength' => 4, 'class' => 'form-control', 'oninput'=>"javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"]) !!}
            @if ($errors->has('tin_anofim')) <p class="help-block">{{ $errors->first('tin_anofim') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <label class="control-label" style="visibility: hidden">Botão</label>
        <div class="controls">
            {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
        </div>
    </div>
</div>
