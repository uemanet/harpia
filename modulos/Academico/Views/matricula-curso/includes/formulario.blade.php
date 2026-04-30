<div class="row">
    <div class="form-group col-md-3 @if($errors->has('crs_id')) has-error @endif">
        <label for="crs_id" class="form-label">Curso <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <select name="crs_id" id="crs_id" class="form-control">
                <option value="">Selecione o curso</option>
                @foreach($cursos as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
            @if ($errors->has('crs_id')) <p class="help-block">{{ $errors->first('crs_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if($errors->has('ofc_id')) has-error @endif">
        <label for="ofc_id" class="form-label">Oferta do Curso <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <select name="ofc_id" id="ofc_id" class="form-control"></select>
            @if ($errors->has('ofc_id')) <p class="help-block">{{ $errors->first('ofc_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if($errors->has('mat_trm_id')) has-error @endif">
        <label for="mat_trm_id" class="form-label">Turma <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <select name="mat_trm_id" id="mat_trm_id" class="form-control grp"></select>
            @if ($errors->has('mat_trm_id')) <p class="help-block">{{ $errors->first('mat_trm_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if($errors->has('mat_pol_id')) has-error @endif">
        <label for="mat_pol_id" class="form-label">Polo <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <select name="mat_pol_id" id="mat_pol_id" class="form-control grp"></select>
            @if ($errors->has('mat_pol_id')) <p class="help-block">{{ $errors->first('mat_pol_id') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-3 @if($errors->has('mat_modo_entrada')) has-error @endif">
        <label for="mat_modo_entrada" class="form-label">Modo de Entrada <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <select name="mat_modo_entrada" id="mat_modo_entrada" class="form-control">
                <option value="">Selecione o modo</option>
                @foreach($modosEntrada as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
            @if ($errors->has('mat_modo_entrada')) <p class="help-block">{{ $errors->first('mat_modo_entrada') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if($errors->has('mat_grp_id')) has-error @endif">
        <label for="mat_grp_id" class="form-label">Grupo</label>
        <div class="controls">
            <select name="mat_grp_id" id="mat_grp_id" class="form-control"></select>
            @if ($errors->has('mat_grp_id')) <p class="help-block">{{ $errors->first('mat_grp_id') }}</p> @endif
        </div>
    </div>
</div>

@section('scripts')
    @vite('modulos/Academico/Resources/js/pages/matricula-curso/formulario.js')
@stop