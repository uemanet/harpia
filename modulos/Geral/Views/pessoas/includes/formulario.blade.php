<div class="row">
    @if(isset($pessoa->pes_id))
        {!! Form::hidden('pes_id', $pessoa->pes_id) !!}
    @endif
    <div class="form-group col-md-4 @if ($errors->has('pes_nome')) has-error @endif">
        {!! Form::label('pes_nome', 'Nome completo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pes_nome', isset($pessoa->pes_nome) ? $pessoa->pes_nome : old('pes_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_nome')) <p class="help-block">{{ $errors->first('pes_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('pes_email')) has-error @endif">
        {!! Form::label('pes_email', 'Email*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::email('pes_email', isset($pessoa->pes_email) ? $pessoa->pes_email : old('pes_email'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_email')) <p class="help-block">{{ $errors->first('pes_email') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('pes_cpf')) has-error @endif">
        {!! Form::label('pes_cpf', 'CPF*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pes_cpf', isset($pessoa->pes_cpf) ? $pessoa->pes_cpf : old('pes_cpf'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_cpf')) <p class="help-block">{{ $errors->first('pes_cpf') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('pes_mae')) has-error @endif">
        {!! Form::label('pes_mae', 'Nome da mãe*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pes_mae', isset($pessoa->pes_mae) ? $pessoa->pes_mae : old('pes_mae'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_mae')) <p class="help-block">{{ $errors->first('pes_mae') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('pes_pai')) has-error @endif">
        {!! Form::label('pes_pai', 'Nome do pai', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pes_pai', isset($pessoa->pes_pai) ? $pessoa->pes_pai : old('pes_pai'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_pai')) <p class="help-block">{{ $errors->first('pes_pai') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-2 @if ($errors->has('pes_sexo')) has-error @endif">
        {!! Form::label('pes_sexo', 'Sexo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('pes_sexo', ['M' => 'Masculino', 'F' => 'Feminino'], isset($pessoa->pes_sexo) ? $pessoa->pes_sexo : old('pes_sexo'), ['class' => 'form-control', 'placeholder' => 'Selecione o sexo']) !!}
            @if ($errors->has('pes_sexo')) <p class="help-block">{{ $errors->first('pes_sexo') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('pes_telefone')) has-error @endif">
        {!! Form::label('pes_telefone', 'Telefone*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pes_telefone', isset($pessoa->pes_telefone) ? $pessoa->pes_telefone : old('pes_telefone'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_telefone')) <p class="help-block">{{ $errors->first('pes_telefone') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 @if ($errors->has('pes_nascimento')) has-error @endif">
        {!! Form::label('pes_nascimento', 'Nascimento*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::date('pes_nascimento', isset($pessoa->pes_nascimento) ? $pessoa->pes_nascimento : old('pes_nascimento'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_nascimento')) <p class="help-block">{{ $errors->first('pes_nascimento') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 @if ($errors->has('pes_estado_civil')) has-error @endif">
        {!! Form::label('pes_estado_civil', 'Estado civil*', ['class' => 'control-label']) !!}

        <div class="controls">
            {!! Form::select('pes_estado_civil',
                                ["solteiro" => "Solteiro(a)",
                                  "casado" => "Casado(a)",
                                  "divorciado" => "Divorciado(a)",
                                  "uniao_estavel" => "União estável",
                                  "viuvo" => "Viúvo(a)",
                                  "outro" => "Outro"],
                                 isset($pessoa->pes_estado_civil) ? $pessoa->pes_estado_civil : old('pes_estado_civil'),
                                 ['class' => 'form-control', 'placeholder' => 'Selecione o estado civil']) !!}
            @if ($errors->has('pes_estado_civil')) <p class="help-block">{{ $errors->first('pes_estado_civil') }}</p> @endif
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
                         isset($pessoa->pes_raca) ? $pessoa->pes_raca : old('pes_raca'),
                         ['class' => 'form-control', 'placeholder' => 'Selecione a raça']) !!}
            @if ($errors->has('pes_raca')) <p class="help-block">{{ $errors->first('pes_raca') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-3 @if ($errors->has('pes_naturalidade')) has-error @endif">
        {!! Form::label('pes_naturalidade', 'Naturalidade*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pes_naturalidade', isset($pessoa->pes_naturalidade) ? $pessoa->pes_naturalidade : old('pes_naturalidade'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_naturalidade')) <p class="help-block">{{ $errors->first('pes_naturalidade') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('pes_nacionalidade')) has-error @endif">
        {!! Form::label('pes_nacionalidade', 'Nacionalidade*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('pes_nacionalidade', isset($pessoa->pes_nacionalidade) ? $pessoa->pes_nacionalidade : old('pes_nacionalidade'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_nacionalidade')) <p class="help-block">{{ $errors->first('pes_nacionalidade') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('pes_necessidade_especial')) has-error @endif">
        {!! Form::label('pes_necessidade_especial', 'Necessidade especial?*', ['class' => 'control-label']) !!}

        <div class="controls">
            {!! Form::select('pes_necessidade_especial', ['S' => 'Sim', 'N' => 'Não'], isset($pessoa->pes_necessidade_especial) ? $pessoa->pes_necessidade_especial : old('pes_necessidade_especial'), ['class' => 'form-control']) !!}
            @if ($errors->has('pes_necessidade_especial')) <p class="help-block">{{ $errors->first('pes_necessidade_especial') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3" @if ($errors->has('pes_estrangeiro')) has-error @endif>
        {!! Form::label('pes_estrangeiro', 'Estrangeiro?*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('pes_estrangeiro', ['1' => 'Sim', '0' => 'Não'], isset($pessoa->pes_estrangeiro) ? $pessoa->pes_estrangeiro : old('pes_estrangeiro'), ['class' => 'form-control', 'placeholder' => 'Selecione']) !!}
            @if ($errors->has('pes_estrangeiro')) <p class="help-block">{{ $errors->first('pes_estrangeiro') }}</p> @endif
        </div>
    </div>
</div>