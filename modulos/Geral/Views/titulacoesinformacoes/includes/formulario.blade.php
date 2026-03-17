<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('tin_tit_id')) has-error @endif">
        <label for="tin_tit_id" class="form-label">Titulações*</label>
        <div class="controls">
            <select name="tin_tit_id" class="form-control">
                <option value="">Selecione uma titulação</option>
                @foreach($titulacoes as $key => $value)
                    <option value="{{ $key }}" {{ old('tin_tit_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
            @if ($errors->has('tin_tit_id')) <p class="help-block">{{ $errors->first('tin_tit_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('tin_titulo')) has-error @endif">
        <label for="tin_titulo" class="form-label">Título/Curso*</label>
        <div class="controls">
            <input type="text" name="tin_titulo" value="{{ old('tin_titulo') }}" class="form-control" >
            @if ($errors->has('tin_titulo')) <p class="help-block">{{ $errors->first('tin_titulo') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('tin_instituicao')) has-error @endif">
      <label for="tin_instituicao" class="form-label">Instituição*</label>
      <div class="controls">
        <input type="text" name="tin_instituicao" value="{{ old('tin_instituicao') }}" class="form-control" >
        @if ($errors->has('tin_instituicao')) <p class="help-block">{{ $errors->first('tin_instituicao') }}</p> @endif
      </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('tin_instituicao_sigla')) has-error @endif">
        <label for="tin_instituicao_sigla" class="form-label">Instituição Sigla*</label>
        <div class="controls">
            <input type="text" name="tin_instituicao_sigla" value="{{ old('tin_instituicao_sigla') }}" class="form-control" >
            @if ($errors->has('tin_instituicao_sigla')) <p class="help-block">{{ $errors->first('tin_instituicao_sigla') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('tin_instituicao_sede')) has-error @endif">
        <label for="tin_instituicao_sede" class="form-label">Instituição Sede*</label>
        <div class="controls">
            <input type="text" name="tin_instituicao_sede" value="{{ old('tin_instituicao_sede') }}" class="form-control" >
            @if ($errors->has('tin_instituicao_sede')) <p class="help-block">{{ $errors->first('tin_instituicao_sede') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 @if ($errors->has('tin_anoinicio')) has-error @endif">
        <label for="tin_anoinicio" class="form-label">Ano Inicio*</label>
        <div class="controls">
            <input type="number" name="tin_anoinicio" value="{{ old('tin_anoinicio') }}" min="1" max="9999" maxlength="4" class="form-control" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" >
            @if ($errors->has('tin_anoinicio')) <p class="help-block">{{ $errors->first('tin_anoinicio') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 @if ($errors->has('tin_anofim')) has-error @endif">
        <label for="tin_anofim" class="form-label">Ano Fim</label>
        <div class="controls">
            <input type="number" name="tin_anofim" value="{{ old('tin_anofim') }}" min="1" max="9999" maxlength="4" class="form-control" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" >
            @if ($errors->has('tin_anofim')) <p class="help-block">{{ $errors->first('tin_anofim') }}</p> @endif
        </div>
    </div>
</div>
