<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('cen_prf_diretor')) has-error @endif">
        <label for="cen_prf_diretor" class="form-label">Diretor <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <select name="cen_prf_diretor" class="form-control">
                <option value="">Selecione o diretor</option>
                @foreach($professores as $key => $value)
                    <option value="{{ $key }}" {{ old('cen_prf_diretor', $centro->cen_prf_diretor ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
            @if ($errors->has('cen_prf_diretor')) <p class="help-block">{{ $errors->first('cen_prf_diretor') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('cen_nome')) has-error @endif">
        <label for="cen_nome" class="form-label">Nome do Centro <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="text" name="cen_nome" value="{{ old('cen_nome', $centro->cen_nome ?? '') }}" class="form-control" >
            @if ($errors->has('cen_nome')) <p class="help-block">{{ $errors->first('cen_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 @if ($errors->has('cen_sigla')) has-error @endif">
        <label for="cen_sigla" class="form-label">Sigla</label>
        <div class="controls">
            <input type="text" name="cen_sigla" value="{{ old('cen_sigla', $centro->cen_sigla ?? '') }}" class="form-control" >
            @if ($errors->has('cen_sigla')) <p class="help-block">{{ $errors->first('cen_sigla') }}</p> @endif
        </div>
    </div>
</div>
