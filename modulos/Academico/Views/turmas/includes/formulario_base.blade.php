<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('trm_nome')) has-error @endif">
        {!! Form::label('trm_nome', 'Nome*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('trm_nome', old('trm_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('trm_nome')) <p class="help-block">{{ $errors->first('trm_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('trm_qtd_vagas')) has-error @endif">
        {!! Form::label('trm_qtd_vagas', 'Quantidade de Vagas*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::number('trm_qtd_vagas', old('trm_qtd_vagas'), ['class' => 'form-control']) !!}
            @if ($errors->has('trm_qtd_vagas')) <p class="help-block">{{ $errors->first('trm_qtd_vagas') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('trm_eh_integrada')) has-error @endif">
        {!! Form::label('trm_eh_integrada', 'É integrada?*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('trm_eh_integrada', array('0' => 'Não', '1' => 'Sim'), null, ['class' => 'form-control', 'placeholder' => 'Selecione']) !!}
            @if ($errors->has('trm_eh_integrada')) <p class="help-block">{{ $errors->first('trm_eh_integrada') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>

@section('scripts')

@stop
