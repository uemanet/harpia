<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('cen_prf_diretor')) has-error @endif">
        {!! Form::label('cen_prf_diretor', 'Diretor*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('cen_prf_diretor', $professores, old('cen_prf_diretor'), ['class' => 'form-control']) !!}
            @if ($errors->has('cen_prf_diretor')) <p class="help-block">{{ $errors->first('cen_prf_diretor') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('cen_nome')) has-error @endif">
        {!! Form::label('cen_nome', 'Nome do Centro*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('cen_nome', old('cen_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('cen_nome')) <p class="help-block">{{ $errors->first('cen_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 @if ($errors->has('cen_nome')) has-error @endif">
        {!! Form::label('cen_sigla', 'Sigla*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('cen_sigla', old('cen_sigla'), ['class' => 'form-control']) !!}
            @if ($errors->has('cen_sigla')) <p class="help-block">{{ $errors->first('cen_sigla') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2">
        <label class="control-label" style="visibility: hidden">Botão</label>
        <div class="controls">
            {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>
</div>