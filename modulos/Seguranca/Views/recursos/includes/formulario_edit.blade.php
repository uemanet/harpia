<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('mod_id')) has-error @endif">
        {!! Form::label('mod_id', 'Módulo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('mod_id', $modulos, old('mod_id'), ['class' => 'form-control', 'placeholder' => 'Selecione um módulo', 'id' => 'mod_id']) !!}
            @if ($errors->has('mod_id')) <p class="help-block">{{ $errors->first('mod_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('rcs_ctr_id')) has-error @endif">
        {!! Form::label('rcs_ctr_id', 'Categoria*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('rcs_ctr_id', $categorias, old('rcs_ctr_id'), ['class' => 'form-control', 'id' => 'rcs_ctr_id']) !!}
            @if ($errors->has('rcs_ctr_id')) <p class="help-block">{{ $errors->first('rcs_ctr_id') }}</p> @endif
        </div>
    </div>
</div>

@include('Seguranca::recursos.includes.formulario_base')