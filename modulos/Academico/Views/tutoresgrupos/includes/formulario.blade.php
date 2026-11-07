<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('ofc_id')) has-error @endif">
        <label for="ofc_id" class="form-label">Ano da Oferta</label>
        <div class="controls">
            <select name="ofc_id" class="form-control" id="ofc_id">
                @foreach($oferta as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
            @if ($errors->has('ofc_id')) <p class="help-block">{{ $errors->first('ofc_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('trm_id')) has-error @endif">
        <label for="trm_id" class="form-label">Turma</label>
        <div class="controls">
            <select name="trm_id" class="form-control" id="trm_id">
                @foreach($turma as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
            @if ($errors->has('trm_id')) <p class="help-block">{{ $errors->first('trm_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('ttg_grp_id')) has-error @endif">
        <label for="ttg_grp_id" class="form-label">Grupo <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <select name="ttg_grp_id" class="form-control" id="ttg_grp_id">
                @foreach($grupo as $key => $value)
                    <option value="{{ $key }}" {{ old('ttg_grp_id') == $key ? 'selected' : '' }}>
                        {{ $value }}
                    </option>
                @endforeach
            </select>
            @if ($errors->has('ttg_grp_id')) <p class="help-block">{{ $errors->first('ttg_grp_id') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('ttg_tut_id')) has-error @endif">
        <label for="ttg_tut_id" class="form-label">Tutor <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <select name="ttg_tut_id" class="form-control" id="ttg_tut_id">
                <option value="">Selecione o tutor</option>
                @foreach($tutores as $key => $value)
                    <option value="{{ $key }}" {{ old('ttg_tut_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
            @if ($errors->has('ttg_tut_id')) <p class="help-block">{{ $errors->first('ttg_tut_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('ttg_tipo_tutoria')) has-error @endif">
        <label for="ttg_tipo_tutoria" class="form-label">Tipo de tutoria <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <select name="ttg_tipo_tutoria" class="form-control" id="ttg_tipo_tutoria">
                <option value="">Selecione o tipo de tutoria</option>
                @foreach($tipostutoria as $key => $value)
                    <option value="{{ $key }}" {{ $tutores == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
            @if ($errors->has('ttg_tipo_tutoria')) <p class="help-block">{{ $errors->first('ttg_tipo_tutoria') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('ttg_data_inicio')) has-error @endif">
        <label for="ttg_data_inicio" class="form-label">Data de Admissão do tutor <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="text" name="ttg_data_inicio" value="{{ old('ttg_data_inicio') }}" class="form-control datepicker" data-provide="datepicker" date-date-format="dd/mm/yyyy" >
            @if ($errors->has('ttg_data_inicio')) <p class="help-block">{{ $errors->first('ttg_data_inicio') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-md-12">
        <button type="submit" class="btn btn-primary pull-right">Salvar dados</button>
    </div>
</div>
