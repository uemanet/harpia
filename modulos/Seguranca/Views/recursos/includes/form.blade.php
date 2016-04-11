<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('rcs_mod_id')) has-error @endif">
        {!! Form::label('rcs_mod_id', 'Módulo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('rcs_mod_id', $modulos, old('rcs_mod_id'), ['class'=>'form-control coletanea', 'aria-required'=>'true']) !!}
            @if ($errors->has('rcs_mod_id')) <p class="help-block">{{ $errors->first('rcs_mod_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('rcs_ctr_id')) has-error @endif">
        {!! Form::label('rcs_ctr_id', 'Categoria*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('rcs_ctr_id', $categorias, old('rcs_ctr_id'), ['class'=>'form-control coletanea', 'aria-required'=>'true']) !!}
            @if ($errors->has('rcs_ctr_id')) <p class="help-block">{{ $errors->first('rcs_ctr_id') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('rcs_nome')) has-error @endif">
        {!! Form::label('rcs_nome', 'Nome do recurso*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('rcs_nome', old('rcs_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('rcs_nome')) <p class="help-block">{{ $errors->first('rcs_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-8 @if ($errors->has('rcs_descricao')) has-error @endif">
        {!! Form::label('rcs_descricao', 'Descrição do recurso', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('rcs_descricao', old('rcs_descricao'), ['class' => 'form-control']) !!}
            @if ($errors->has('rcs_descricao')) <p class="help-block">{{ $errors->first('rcs_descricao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('rcs_icone')) has-error @endif">
        {!! Form::label('rcs_icone', 'Ícone do recurso', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('rcs_icone', old('rcs_icone'), ['class' => 'form-control']) !!}
            @if ($errors->has('rcs_icone')) <p class="help-block">{{ $errors->first('rcs_icone') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('rcs_ordem')) has-error @endif">
        {!! Form::label('rcs_ordem', 'Ordem do recurso', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::number('rcs_ordem', old('rcs_ordem'), ['class' => 'form-control']) !!}
            @if ($errors->has('rcs_ordem')) <p class="help-block">{{ $errors->first('rcs_ordem') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('rcs_ativo')) has-error @endif">
        {!! Form::label('rcs_ativo', 'Status*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('rcs_ativo', ['1' => 'Ativo', '0' => 'Inativo'], null, ['class' => 'form-control']) !!}
            @if ($errors->has('rcs_ativo')) <p class="help-block">{{ $errors->first('rcs_ativo') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>