<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('crs_id')) has-error @endif">
        <label for="crs_id" class="form-label">Curso <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <select name="crs_id" class="form-control select-control">
                @foreach($curso as $key => $value)
                    <option value="{{ $key }}" {{ old('crs_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
            @if ($errors->has('crs_id')) <p class="help-block">{{ $errors->first('crs_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if($errors->has('ofc_id')) has-error @endif">
        <label for="ofc_id" class="form-label">Oferta de Curso <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <select name="ofc_id" class="form-control select-control">
                @foreach($oferta as $key => $value)
                    <option value="{{ $key }}" {{ old('ofc_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
            @if ($errors->has('ofc_id')) <p class="help-block">{{ $errors->first('crs_id')}} </p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('grp_trm_id')) has-error @endif">
        <label for="grp_trm_id" class="form-label">Turma <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <select name="grp_trm_id" class="form-control">
                @foreach($turma as $key => $value)
                    <option value="{{ $key }}" {{ old('grp_trm_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
            @if ($errors->has('grp_trm_id')) <p class="help-block">{{ $errors->first('grp_trm_id') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('grp_pol_id')) has-error @endif">
        <label for="grp_pol_id" class="form-label">Polo <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <select name="grp_pol_id" class="form-control">
                @foreach($polos as $key => $value)
                    <option value="{{ $key }}" {{ old('grp_pol_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
            @if ($errors->has('grp_pol_id')) <p class="help-block">{{ $errors->first('grp_pol_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('grp_nome')) has-error @endif">
        <label for="grp_nome" class="form-label">Nome do Grupo <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="text" name="grp_nome" value="{{ old('grp_nome', $grupo->grp_nome) }}" class="form-control select-control" >
            @if ($errors->has('grp_nome')) <p class="help-block">{{ $errors->first('grp_nome') }}</p> @endif
        </div>
    </div>
    <!-- </div> -->
    <!-- <div class="row"> -->
    <div class="form-group col-md-2">
        <label class="form-label" style="visibility: hidden">Submit</label>
        <div class="controls">
            <button type="submit" class="btn btn-primary">Salvar dados</button>
        </div>
    </div>
</div>

