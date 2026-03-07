<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('ofc_id')) has-error @endif">
        <label for="ofc_id" class="control-label">Ano da Oferta</label>
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
        <label for="trm_id" class="control-label">Turma</label>
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
        <label for="ttg_grp_id" class="control-label">Grupo*</label>
        <div class="controls">
            <select name="ttg_grp_id" class="form-control" id="ttg_grp_id">
    @foreach($grupo as $key => $value)
        <option value="{{ $key }}" {{ $grupo == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
            @if ($errors->has('ttg_grp_id')) <p class="help-block">{{ $errors->first('ttg_grp_id') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('ttg_tut_id')) has-error @endif">
        <label for="ttg_tut_id" class="control-label">Novo Tutor*</label>
        <div class="controls">
            <select name="ttg_tut_id" class="form-control" id="ttg_tut_id">
    <option value="">Selecione o novo tutor</option>
    @foreach($tutores as $key => $value)
        <option value="{{ $key }}" {{ $tutores == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
            @if ($errors->has('ttg_tut_id')) <p class="help-block">{{ $errors->first('ttg_tut_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('ttg_tipo_tutoria')) has-error @endif">
        <label for="ttg_tipo_tutoria" class="control-label">Tipo de tutoria*</label>
        <div class="controls">
          @if ($tutorgrupo->getRawOriginal('ttg_tipo_tutoria') === "presencial")
            <select name="ttg_tipo_tutoria" class="form-control" id="ttg_tipo_tutoria">
    @foreach(array('presencial' => 'Presencial') as $key => $value)
        <option value="{{ $key }}" {{ $tutores == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
          @endif
          @if ($tutorgrupo->getRawOriginal('ttg_tipo_tutoria') === "distancia")
            <select name="ttg_tipo_tutoria" class="form-control" id="ttg_tipo_tutoria">
    @foreach(array('distancia' => 'A Distância') as $key => $value)
        <option value="{{ $key }}" {{ $tutores == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
          @endif
            @if ($errors->has('ttg_tipo_tutoria')) <p class="help-block">{{ $errors->first('ttg_tipo_tutoria') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('ttg_data_inicio')) has-error @endif">
        <label for="ttg_data_inicio" class="control-label">Data de Admissão do tutor*</label>
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
