<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('mod_id')) has-error @endif">
        {!! Form::label('mod_id', 'Módulo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('mod_id', $modulos, old('mod_id'), ['class' => 'form-control', 'placeholder' => 'Selecione um módulo']) !!}
            @if ($errors->has('mod_id')) <p class="help-block">{{ $errors->first('mod_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('prm_rcs_id')) has-error @endif">
        {!! Form::label('prm_rcs_id', 'Recurso*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('prm_rcs_id', $recursos, old('prm_rcs_id'), ['class' => 'form-control', 'id' => 'prm_rcs_id']) !!}
            @if ($errors->has('prm_rcs_id')) <p class="help-block">{{ $errors->first('prm_rcs_id') }}</p> @endif
        </div>
    </div>
</div>

@include('Seguranca::permissoes.includes.formulario_base')