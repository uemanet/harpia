<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('usr_nome')) has-error @endif">
        {!! Form::label('usr_nome', 'Nome Completo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('usr_nome', old('usr_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('usr_nome')) <p class="help-block">{{ $errors->first('usr_nome') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 col-sm-4 col-xs-6 @if ($errors->has('usr_email')) has-error @endif">
        {!! Form::label('usr_email', 'Email*', ['class' => 'control-label']) !!}
        <div class="controls    ">
            {!! Form::email('usr_email', old('usr_email'), ['class' => 'form-control']) !!}
            @if ($errors->has('usr_email')) <p class="help-block">{{ $errors->first('usr_email') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 col-xs-6 @if ($errors->has('usr_telefone')) has-error @endif">
        {!! Form::label('usr_telefone', 'Celular', ['class' => 'control-label']) !!}
        <div class="controls    2">
            {!! Form::text('usr_telefone', old('usr_telefone'), ['class' => 'form-control', 'data-mask' => '99 99999-9999']) !!}
            @if ($errors->has('usr_telefone')) <p class="help-block">{{ $errors->first('usr_telefone') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 col-sm-4 @if ($errors->has('usr_usuario')) has-error @endif">
        {!! Form::label('usr_usuario', 'Usuário*', ['class' => 'control-label']) !!}
        <div class="controls    ">
            {!! Form::text('usr_usuario', old('usr_usuario'), ['class' => 'form-control']) !!}
            @if ($errors->has('usr_usuario')) <p class="help-block">{{ $errors->first('usr_usuario') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 col-sm-4 @if ($errors->has('usr_senha')) has-error @endif">
        {!! Form::label('usr_senha', 'Senha*', ['class' => 'control-label']) !!}
        <div class="controls    ">
            {!! Form::password('usr_senha', ['class' => 'form-control']) !!}
            @if ($errors->has('usr_senha')) <p class="help-block">{{ $errors->first('usr_senha') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 col-sm-4 @if ($errors->has('usr_ativo')) has-error @endif">
        {!! Form::label('usr_ativo', 'Status*', ['class' => 'control-label']) !!}
        <div class="controls    ">
            {!! Form::select('usr_ativo', ['1' => 'Ativo', '0' => 'Inativo'], null, ['class' => 'form-control']) !!}
            @if ($errors->has('usr_ativo')) <p class="help-block">{{ $errors->first('usr_ativo') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>
