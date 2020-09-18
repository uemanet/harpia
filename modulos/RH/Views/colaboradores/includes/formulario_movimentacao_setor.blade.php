<div class="row">
    <div class="form-group col-md-3 @if ($errors->has('col_set_id')) has-error @endif">
        {!! Form::label('col_set_id', 'Setor*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('col_set_id', $setores, isset($colaborador->col_set_id) ? $colaborador->col_set_id : old('col_set_id'), ['class' => 'form-control', 'placeholder' => 'Selecione o setor']) !!}
            @if ($errors->has('col_set_id')) <p class="help-block">{{ $errors->first('col_set_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('col_fun_id')) has-error @endif">
        {!! Form::label('col_fun_id', 'Função*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('col_fun_id', $funcoes, isset($colaborador->col_fun_id) ? $colaborador->col_fun_id : old('col_fun_id'), ['class' => 'form-control', 'placeholder' => 'Selecione a função']) !!}
            @if ($errors->has('col_fun_id')) <p class="help-block">{{ $errors->first('col_fun_id') }}</p> @endif
        </div>
    </div>
</div>
