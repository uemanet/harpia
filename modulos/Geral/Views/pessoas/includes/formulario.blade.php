<div class="row">
    @if(isset($pessoa->pes_id))
        <input type="hidden" name="pes_id" value="{{ $pessoa->pes_id }}" >
    @endif

    <h3 class="card-title w-100 pb-3">
        <span style="font-weight: bold;"><i class="fa-solid fa-caret-right"></i> Dados Pessoais</span>
    </h3>

    <div class="form-group col-md-4 @if ($errors->has('pes_nome')) has-error @endif">
        <label for="pes_nome" class="form-label">Nome completo*</label>
        <div class="controls">
            <input type="text" name="pes_nome" value="{{ isset($pessoa->pes_nome) ? $pessoa->pes_nome : old('pes_nome') }}" class="form-control" >
            @if ($errors->has('pes_nome')) <p class="help-block">{{ $errors->first('pes_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('pes_email')) has-error @endif">
        <label for="pes_email" class="form-label">Email*</label>
        <div class="controls">
            <input type="email" name="pes_email" value="{{ isset($pessoa->pes_email) ? $pessoa->pes_email : old('pes_email') }}" class="form-control" >
            @if ($errors->has('pes_email')) <p class="help-block">{{ $errors->first('pes_email') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('doc_conteudo')) has-error @endif">
        <label for="doc_conteudo" class="form-label">CPF*</label>
        <div class="controls">
            <input type="text" name="doc_conteudo" value="{{ isset($pessoa->doc_conteudo) ? $pessoa->doc_conteudo : old('doc_conteudo') }}" class="form-control cpf-mask" >
            @if ($errors->has('doc_conteudo')) <p class="help-block">{{ $errors->first('doc_conteudo') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('pes_mae')) has-error @endif">
        <label for="pes_mae" class="form-label">Nome da mãe*</label>
        <div class="controls">
            <input type="text" name="pes_mae" value="{{ isset($pessoa->pes_mae) ? $pessoa->pes_mae : old('pes_mae') }}" class="form-control" >
            @if ($errors->has('pes_mae')) <p class="help-block">{{ $errors->first('pes_mae') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('pes_pai')) has-error @endif">
        <label for="pes_pai" class="form-label">Nome do pai</label>
        <div class="controls">
            <input type="text" name="pes_pai" value="{{ isset($pessoa->pes_pai) ? $pessoa->pes_pai : old('pes_pai') }}" class="form-control" >
            @if ($errors->has('pes_pai')) <p class="help-block">{{ $errors->first('pes_pai') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-2 @if ($errors->has('pes_sexo')) has-error @endif">
        <label for="pes_sexo" class="form-label">Sexo*</label>
        <div class="controls">
            <select name="pes_sexo" class="form-control">
                <option value="">Selecione o sexo</option>
                <option value="M" {{ isset($pessoa->pes_sexo) && $pessoa->pes_sexo == 'M' ? 'selected' : '' }}>Masculino</option>
                <option value="F" {{ isset($pessoa->pes_sexo) && $pessoa->pes_sexo == 'F' ? 'selected' : '' }}>Feminino</option>
            </select>
            @if ($errors->has('pes_sexo')) <p class="help-block">{{ $errors->first('pes_sexo') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('pes_telefone')) has-error @endif">
        <label for="pes_telefone" class="form-label">Telefone*</label>
        <div class="controls">
            <input type="text" name="pes_telefone" value="{{ isset($pessoa->pes_telefone) ? $pessoa->pes_telefone : old('pes_telefone') }}" class="form-control" >
            @if ($errors->has('pes_telefone')) <p class="help-block">{{ $errors->first('pes_telefone') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 @if ($errors->has('pes_nascimento')) has-error @endif">
        <label for="pes_nascimento" class="form-label">Nascimento*</label>
        <div class="controls">
            <input type="text" name="pes_nascimento" value="{{ isset($pessoa->pes_nascimento) ? $pessoa->pes_nascimento : old('pes_nascimento') }}" class="form-control datepicker" >
            @if ($errors->has('pes_nascimento')) <p class="help-block">{{ $errors->first('pes_nascimento') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 @if ($errors->has('pes_estado_civil')) has-error @endif">
        <label for="pes_estado_civil" class="form-label">Estado civil</label>

        <div class="controls">
            <select name="pes_estado_civil" class="form-control">
                <option value="">Selecione o estado civil</option>
                <option value="uniao_estavel" {{ isset($pessoa->pes_estado_civil) && $pessoa->pes_estado_civil == 'uniao_estavel' ? 'selected' : '' }}>União estável</option>
            </select>
            @if ($errors->has('pes_estado_civil')) <p class="help-block">{{ $errors->first('pes_estado_civil') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('pes_raca')) has-error @endif">
        <label for="pes_raca" class="form-label">Cor/Raça</label>

        <div class="controls">
            <select name="pes_raca" class="form-control">
                <option value="">Selecione a raça</option>
                <option value="branca" {{ isset($pessoa->pes_raca) && $pessoa->pes_raca == 'branca' ? 'selected' : '' }}>Branca</option>
                <option value="preta" {{ isset($pessoa->pes_raca) && $pessoa->pes_raca == 'preta' ? 'selected' : '' }}>Preta</option>
                <option value="parda" {{ isset($pessoa->pes_raca) && $pessoa->pes_raca == 'parda' ? 'selected' : '' }}>Parda</option>
                <option value="amarela" {{ isset($pessoa->pes_raca) && $pessoa->pes_raca == 'amarela' ? 'selected' : '' }}>Amarela</option>
                <option value="indigena" {{ isset($pessoa->pes_raca) && $pessoa->pes_raca == 'indigena' ? 'selected' : '' }}>Indígena</option>
                <option value="outra" {{ isset($pessoa->pes_raca) && $pessoa->pes_raca == 'outra' ? 'selected' : '' }}>Outra</option>
            </select>
            @if ($errors->has('pes_raca')) <p class="help-block">{{ $errors->first('pes_raca') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-3 @if ($errors->has('pes_naturalidade')) has-error @endif">
        <label for="pes_naturalidade" class="form-label">Naturalidade*</label>
        <div class="controls">
            <input type="text" name="pes_naturalidade" value="{{ isset($pessoa->pes_naturalidade) ? $pessoa->pes_naturalidade : old('pes_naturalidade') }}" class="form-control" >
            @if ($errors->has('pes_naturalidade')) <p class="help-block">{{ $errors->first('pes_naturalidade') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('pes_nacionalidade')) has-error @endif">
        <label for="pes_nacionalidade" class="form-label">Nacionalidade*</label>
        <div class="controls">
            <input type="text" name="pes_nacionalidade" value="{{ isset($pessoa->pes_nacionalidade) ? $pessoa->pes_nacionalidade : old('pes_nacionalidade') }}" class="form-control" >
            @if ($errors->has('pes_nacionalidade')) <p class="help-block">{{ $errors->first('pes_nacionalidade') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('pes_necessidade_especial')) has-error @endif">
        <label for="pes_necessidade_especial" class="form-label">Necessidade especial?</label>

        <div class="controls">
            <select name="pes_necessidade_especial" class="form-control">
                <option value="N" {{ isset($pessoa->pes_necessidade_especial) && $pessoa->pes_necessidade_especial == 'N' ? 'selected' : '' }}>Não</option>
                <option value="S" {{ isset($pessoa->pes_necessidade_especial) && $pessoa->pes_necessidade_especial == 'S' ? 'selected' : '' }}>Sim</option>
            </select>
            @if ($errors->has('pes_necessidade_especial')) <p class="help-block">{{ $errors->first('pes_necessidade_especial') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3" @if ($errors->has('pes_estrangeiro')) has-error @endif>
        <label for="pes_estrangeiro" class="form-label">Estrangeiro?</label>
        <div class="controls">
            <select name="pes_estrangeiro" class="form-control">
                <option value="0" {{ isset($pessoa->pes_estrangeiro) && $pessoa->pes_estrangeiro == '0' ? 'selected' : '' }}>Não</option>
                <option value="1" {{ isset($pessoa->pes_estrangeiro) && $pessoa->pes_estrangeiro == '1' ? 'selected' : '' }}>Sim</option>
            </select>
            @if ($errors->has('pes_estrangeiro')) <p class="help-block">{{ $errors->first('pes_estrangeiro') }}</p> @endif
        </div>
    </div>
</div>

<hr class="my-3">
<h3 class="card-title w-100 pb-3">
    <span style="font-weight: bold;"><i class="fa-solid fa-caret-right"></i> Endereço</span>
</h3>

<div class="row">
    <div class="form-group col-md-2 @if ($errors->has('pes_cep')) has-error @endif">
        <label for="pes_cep" class="form-label">CEP*</label>
        <div class="controls">
            <input type="text" name="pes_cep" value="{{ isset($pessoa->pes_cep) ? $pessoa->pes_cep : old('pes_cep') }}" class="form-control" >
            @if ($errors->has('pes_cep')) <p class="help-block">{{ $errors->first('pes_cep') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('pes_endereco')) has-error @endif">
        <label for="pes_endereco" class="form-label">Endereço*</label>
        <div class="controls">
            <input type="text" name="pes_endereco" value="{{ isset($pessoa->pes_endereco) ? $pessoa->pes_endereco : old('pes_endereco') }}" class="form-control" >
            @if ($errors->has('pes_endereco')) <p class="help-block">{{ $errors->first('pes_endereco') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('pes_complemento')) has-error @endif">
        <label for="pes_complemento" class="form-label">Complemento</label>
        <div class="controls">
            <input type="text" name="pes_complemento" value="{{ isset($pessoa->pes_complemento) ? $pessoa->pes_complemento : old('pes_complemento') }}" class="form-control" >
            @if ($errors->has('pes_complemento')) <p class="help-block">{{ $errors->first('pes_complemento') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-2 @if ($errors->has('pes_numero')) has-error @endif">
        <label for="pes_numero" class="form-label">Número*</label>
        <div class="controls">
            <input type="text" name="pes_numero" value="{{ isset($pessoa->pes_numero) ? $pessoa->pes_numero : old('pes_numero') }}" class="form-control" >
            @if ($errors->has('pes_numero')) <p class="help-block">{{ $errors->first('pes_numero') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('pes_bairro')) has-error @endif">
        <label for="pes_bairro" class="form-label">Bairro*</label>
        <div class="controls">
            <input type="text" name="pes_bairro" value="{{ isset($pessoa->pes_bairro) ? $pessoa->pes_bairro : old('pes_bairro') }}" class="form-control" >
            @if ($errors->has('pes_bairro')) <p class="help-block">{{ $errors->first('pes_bairro') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if ($errors->has('pes_cidade')) has-error @endif">
        <label for="pes_cidade" class="form-label">Cidade*</label>
        <div class="controls">
            <input type="text" name="pes_cidade" value="{{ isset($pessoa->pes_cidade) ? $pessoa->pes_cidade : old('pes_cidade') }}" class="form-control" >
            @if ($errors->has('pes_cidade')) <p class="help-block">{{ $errors->first('pes_cidade') }}</p> @endif
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group @if ($errors->has('pes_estado')) has-error @endif">
            <label for="pes_estado" class="form-label">Estado*</label>
            <select name="pes_estado" class="form-control">
                <option value="">Selecione uma opção...</option>
                <option value="AC" {{ isset($pessoa->pes_estado) && $pessoa->pes_estado == 'AC' ? 'selected' : '' }}>Acre</option>
                <option value="AL" {{ isset($pessoa->pes_estado) && $pessoa->pes_estado == 'AL' ? 'selected' : '' }}>Alagoas</option>
                <option value="AP" {{ isset($pessoa->pes_estado) && $pessoa->pes_estado == 'AP' ? 'selected' : '' }}>Amapá</option>
                <option value="AM" {{ isset($pessoa->pes_estado) && $pessoa->pes_estado == 'AM' ? 'selected' : '' }}>Amazonas</option>
                <option value="BA" {{ isset($pessoa->pes_estado) && $pessoa->pes_estado == 'BA' ? 'selected' : '' }}>Bahia</option>
                <option value="CE" {{ isset($pessoa->pes_estado) && $pessoa->pes_estado == 'CE' ? 'selected' : '' }}>Ceará</option>
                <option value="DF" {{ isset($pessoa->pes_estado) && $pessoa->pes_estado == 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                <option value="ES" {{ isset($pessoa->pes_estado) && $pessoa->pes_estado == 'ES' ? 'selected' : '' }}>Espirito Santo</option>
                <option value="GO" {{ isset($pessoa->pes_estado) && $pessoa->pes_estado == 'GO' ? 'selected' : '' }}>Goiás</option>
                <option value="MA" {{ isset($pessoa->pes_estado) && $pessoa->pes_estado == 'MA' ? 'selected' : '' }}>Maranhão</option>
                <option value="MT" {{ isset($pessoa->pes_estado) && $pessoa->pes_estado == 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                <option value="MS" {{ isset($pessoa->pes_estado) && $pessoa->pes_estado == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                <option value="MG" {{ isset($pessoa->pes_estado) && $pessoa->pes_estado == 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                <option value="PA" {{ isset($pessoa->pes_estado) && $pessoa->pes_estado == 'PA' ? 'selected' : '' }}>Pará</option>
                <option value="PB" {{ isset($pessoa->pes_estado) && $pessoa->pes_estado == 'PB' ? 'selected' : '' }}>Paraiba</option>
                <option value="PR" {{ isset($pessoa->pes_estado) && $pessoa->pes_estado == 'PR' ? 'selected' : '' }}>Paraná</option>
                <option value="PE" {{ isset($pessoa->pes_estado) && $pessoa->pes_estado == 'PE' ? 'selected' : '' }}>Pernambuco</option>
                <option value="PI" {{ isset($pessoa->pes_estado) && $pessoa->pes_estado == 'PI' ? 'selected' : '' }}>Piauí</option>
                <option value="RJ" {{ isset($pessoa->pes_estado) && $pessoa->pes_estado == 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                <option value="RN" {{ isset($pessoa->pes_estado) && $pessoa->pes_estado == 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
                <option value="RS" {{ isset($pessoa->pes_estado) && $pessoa->pes_estado == 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                <option value="RO" {{ isset($pessoa->pes_estado) && $pessoa->pes_estado == 'RO' ? 'selected' : '' }}>Rondônia</option>
                <option value="RR" {{ isset($pessoa->pes_estado) && $pessoa->pes_estado == 'RR' ? 'selected' : '' }}>Roraima</option>
                <option value="SC" {{ isset($pessoa->pes_estado) && $pessoa->pes_estado == 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                <option value="SP" {{ isset($pessoa->pes_estado) && $pessoa->pes_estado == 'SP' ? 'selected' : '' }}>São Paulo</option>
                <option value="SE" {{ isset($pessoa->pes_estado) && $pessoa->pes_estado == 'SE' ? 'selected' : '' }}>Sergipe</option>
                <option value="TO" {{ isset($pessoa->pes_estado) && $pessoa->pes_estado == 'TO' ? 'selected' : '' }}>Tocantis</option>
            </select>
            @if ($errors->has('pes_estado')) <p class="help-block">{{ $errors->first('pes_estado') }}</p> @endif
        </div>
    </div>
</div>
