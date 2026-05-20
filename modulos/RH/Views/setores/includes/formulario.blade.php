<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('set_descricao')) has-error @endif">
        <label for="set_descricao" class="form-label">Descrição*</label>
        <div class="controls">
            <input type="text" name="set_descricao" value="{{ old('set_descricao', $setor->set_descricao ?? '') }}" class="form-control" >
            @if ($errors->has('set_descricao')) <p class="help-block">{{ $errors->first('set_descricao') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('set_sigla')) has-error @endif">
        <label for="set_sigla" class="form-label">Sigla*</label>
        <div class="controls">
            <input type="text" name="set_sigla" value="{{ old('set_sigla', $setor->set_sigla ?? '') }}" class="form-control" >
            @if ($errors->has('set_sigla')) <p class="help-block">{{ $errors->first('set_sigla') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('gestores')) has-error @endif">
        <label for="gestores" class="control-label">Gestores do Setor</label>
        <div class="controls">
            <select name="gestores[]" class="form-control select2" multiple="multiple" style="width: 100%;"
                    data-placeholder="Selecione os gestores do setor">
                @if(isset($colaboradoresAtivos))
                    @foreach($colaboradoresAtivos as $colaborador)
                        <option value="{{ $colaborador->col_id }}"
                            {{ in_array($colaborador->col_id, old('gestores', isset($gestoresSelecionados) ? $gestoresSelecionados : [])) ? 'selected' : '' }}>
                            {{ $colaborador->pessoa->pes_nome ?? '#' . $colaborador->col_id }}
                        </option>
                    @endforeach
                @endif
            </select>
            <span class="help-block">Selecione um ou mais colaboradores que serao gestores deste setor.</span>
            @if ($errors->has('gestores')) <p class="help-block">{{ $errors->first('gestores') }}</p> @endif
        </div>
    </div>
</div>