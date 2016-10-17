<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('pes_nome')) has-error @endif">
        {!! Form::label('pes_nome', 'Nome completo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pes_nome', old('pes_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_nome')) <p class="help-block">{{ $errors->first('pes_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('pes_email')) has-error @endif">
        {!! Form::label('pes_email', 'Email*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::email('pes_email', old('pes_email'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_email')) <p class="help-block">{{ $errors->first('pes_email') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('pes_mae')) has-error @endif">
        {!! Form::label('pes_mae', 'Nome da mãe*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pes_mae', old('pes_mae'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_mae')) <p class="help-block">{{ $errors->first('pes_mae') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('pes_pai')) has-error @endif">
        {!! Form::label('pes_pai', 'Nome do pai', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pes_pai', old('pes_pai'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_pai')) <p class="help-block">{{ $errors->first('pes_pai') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-3 @if ($errors->has('pes_telefone')) has-error @endif">
        {!! Form::label('pes_telefone', 'Telefone*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pes_telefone', old('pes_telefone'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_telefone')) <p class="help-block">{{ $errors->first('pes_telefone') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('pes_sexo')) has-error @endif">
        {!! Form::label('pes_sexo', 'Sexo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('pes_sexo', ['M' => 'Masculino', 'F' => 'Feminino'], old('pes_sexo'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_sexo')) <p class="help-block">{{ $errors->first('pes_sexo') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('pes_nascimento')) has-error @endif">
        {!! Form::label('pes_nascimento', 'Nascimento*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::date('pes_nascimento', old('pes_nascimento'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_nascimento')) <p class="help-block">{{ $errors->first('pes_nascimento') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('pes_estado_civil')) has-error @endif">
        {!! Form::label('pes_estado_civil', 'Estado civil*', ['class' => 'control-label']) !!}

        <div class="controls">
            {!! Form::select('pes_estado_civil',
                                ["solteiro" => "Solteiro(a)",
                                  "casado" => "Casado(a)",
                                  "divorciado" => "Divorciado(a)",
                                  "uniao_estavel" => "União estável",
                                  "viuvo" => "Viúvo(a)",
                                  "outro" => "Outro"],
                                 old('pes_estado_civil'),
                                 ['class' => 'form-control']) !!}
            @if ($errors->has('pes_estado_civil')) <p class="help-block">{{ $errors->first('pes_estado_civil') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-3 @if ($errors->has('pes_naturalidade')) has-error @endif">
        {!! Form::label('pes_naturalidade', 'Naturalidade*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pes_naturalidade', old('pes_naturalidade'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_naturalidade')) <p class="help-block">{{ $errors->first('pes_naturalidade') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('pes_nacionalidade')) has-error @endif">
        {!! Form::label('pes_nacionalidade', 'Nacionalidade*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pes_nacionalidade', old('pes_nacionalidade'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_nacionalidade')) <p class="help-block">{{ $errors->first('pes_nacionalidade') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('pes_raca')) has-error @endif">
        {!! Form::label('pes_raca', 'Cor/Raça*', ['class' => 'control-label']) !!}

        <div class="controls">
            {!! Form::select('pes_raca',
                        ["branca" => "Branca",
                          "preta" => "Preta",
                          "parda" => "Parda",
                          "amarela" => "Amarela",
                          "indigena" => "Indígena",
                          "outra" => "Outra"],
                         old('pes_raca'),
                         ['class' => 'form-control']) !!}
            @if ($errors->has('pes_raca')) <p class="help-block">{{ $errors->first('pes_raca') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('pes_necessidade_especial')) has-error @endif">
        {!! Form::label('pes_necessidade_especial', 'Necessidade especial?*', ['class' => 'control-label']) !!}

        <div class="controls">
            {!! Form::select('pes_necessidade_especial', ['S' => 'Sim', 'N' => 'Não'], old('pes_necessidade_especial'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_necessidade_especial')) <p class="help-block">{{ $errors->first('pes_necessidade_especial') }}</p> @endif
        </div>
    </div>
</div>