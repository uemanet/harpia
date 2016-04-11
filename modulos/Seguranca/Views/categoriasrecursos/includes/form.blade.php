<div class="row">
    <div class="form-group col-md-9 @if ($errors->has('ctr_nome')) has-error @endif">
        {!! Form::label('ctr_nome', 'Nome da categoria', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('ctr_nome', old('ctr_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('ctr_nome')) <p class="help-block">{{ $errors->first('ctr_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('ctr_icone')) has-error @endif">
        {!! Form::label('ctr_icone', 'Ícone da categoria', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('ctr_icone', old('ctr_icone'), ['class' => 'form-control']) !!}
            @if ($errors->has('ctr_icone')) <p class="help-block">{{ $errors->first('ctr_icone') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('ctr_ordem')) has-error @endif">
        {!! Form::label('ctr_ordem', 'Ordem da categoria', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::number('ctr_ordem', old('ctr_ordem'), ['class' => 'form-control']) !!}
            @if ($errors->has('ctr_ordem')) <p class="help-block">{{ $errors->first('ctr_ordem') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('ctr_ativo')) has-error @endif">
        {!! Form::label('ctr_ativo', 'Status', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('ctr_ativo', ['1' => 'Ativo', '0' => 'Inativo'], null, ['class' => 'form-control']) !!}
            @if ($errors->has('ctr_ativo')) <p class="help-block">{{ $errors->first('ctr_ativo') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>