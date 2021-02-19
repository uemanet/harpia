<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('col_data_admissao')) has-error @endif">
        {!! Form::label('col_data_admissao', 'Data de admissão*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('col_data_admissao',isset($colaborador->col_data_admissao) ? $colaborador->col_data_admissao : old('col_data_admissao'), ['class' => 'form-control datepicker', 'data-provide' => 'datepicker', 'date-date-format' => 'dd/mm/yyyy']) !!}
            @if ($errors->has('col_data_admissao')) <p
                    class="help-block">{{ $errors->first('col_data_admissao') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('col_ch_diaria')) has-error @endif">
        {!! Form::label('col_ch_diaria', 'Carga Horária diária*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::number('col_ch_diaria', isset($colaborador->col_ch_diaria) ? $colaborador->col_ch_diaria : old('col_ch_diaria'),['class' => 'form-control']) !!}
            @if ($errors->has('col_ch_diaria')) <p class="help-block">{{ $errors->first('col_ch_diaria') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('col_codigo_catraca')) has-error @endif">
        {!! Form::label('col_codigo_catraca', 'Código da Catraca*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('col_codigo_catraca', isset($colaborador->col_codigo_catraca) ? $colaborador->col_codigo_catraca : old('col_codigo_catraca'), ['class' => 'form-control']) !!}
            @if ($errors->has('col_codigo_catraca')) <p
                    class="help-block">{{ $errors->first('col_codigo_catraca') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('col_vinculo_universidade')) has-error @endif">
        {!! Form::label('col_vinculo_universidade', 'Vínculo com a universidade?*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('col_vinculo_universidade', array('0' => 'Não', '1' => 'Sim'), isset($colaborador->col_vinculo_universidade) ? $colaborador->col_vinculo_universidade : old('col_vinculo_universidade'), ['class' => 'form-control', 'placeholder' => 'Selecione']) !!}
            @if ($errors->has('col_vinculo_universidade')) <p
                    class="help-block">{{ $errors->first('col_vinculo_universidade') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('col_matricula_universidade')) has-error @endif">
        {!! Form::label('col_matricula_universidade', 'Código da matrícula na universidade*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('col_matricula_universidade', isset($colaborador->col_matricula_universidade) ? $colaborador->col_matricula_universidade : old('col_matricula_universidade'), ['class' => 'form-control']) !!}
            @if ($errors->has('col_matricula_universidade')) <p
                    class="help-block">{{ $errors->first('col_matricula_universidade') }}</p> @endif
        </div>
    </div>

    <div class="form-group col-md-2 @if ($errors->has('col_qtd_filho')) has-error @endif">
        {!! Form::label('col_qtd_filho', 'Quantidade de filhos*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::number('col_qtd_filho', isset($colaborador->col_qtd_filho) ? $colaborador->col_qtd_filho : old('col_qtd_filho'),['class' => 'form-control']) !!}
            @if ($errors->has('col_qtd_filho')) <p class="help-block">{{ $errors->first('col_qtd_filho') }}</p> @endif
        </div>
    </div>

    <div class="form-group col-md-2 @if ($errors->has('col_status')) has-error @endif">
        {!! Form::label('col_status', 'Status*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('col_status', array('ativo' => 'ativo', 'afastado' => 'afastado', 'desligado' => 'desligado'), isset($colaborador->col_status) ? $colaborador->col_status : old('col_status'), ['class' => 'form-control', 'placeholder' => 'Selecione']) !!}
            @if ($errors->has('col_status')) <p class="help-block">{{ $errors->first('col_status') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('col_observacao')) has-error @endif">
        {!! Form::label('col_observacao', 'Observação', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::textarea('col_observacao', isset($colaborador->col_observacao) ? $colaborador->col_observacao : old('col_observacao'), ['class' => 'form-control', 'rows' => '3']) !!}
            @if ($errors->has('col_observacao')) <p class="help-block">{{ $errors->first('col_observacao') }}</p> @endif
        </div>
    </div>
</div>