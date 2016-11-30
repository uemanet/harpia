<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('usr_usuario')) has-error @endif">
        {!! Form::label('usr_usuario', 'Usuário de acesso*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('usr_usuario', old('usr_usuario'), ['class' => 'form-control']) !!}
            @if ($errors->has('usr_usuario')) <p class="help-block">{{ $errors->first('usr_usuario') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('usr_senha')) has-error @endif">
        {!! Form::label('usr_senha', 'Senha*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::password('usr_senha', ['class' => 'form-control']) !!}
            @if ($errors->has('usr_senha')) <p class="help-block">{{ $errors->first('usr_senha') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('usr_ativo')) has-error @endif">
        {!! Form::label('usr_ativo', 'Ativo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('usr_ativo', [1 => "Sim", 0 => "Não"], old('rcs_ctr_id'), ['class' => 'form-control']) !!}
            @if ($errors->has('usr_ativo')) <p class="help-block">{{ $errors->first('usr_ativo') }}</p> @endif
        </div>
    </div>
</div>