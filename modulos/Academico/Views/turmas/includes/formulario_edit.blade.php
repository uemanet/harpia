<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('crs_id')) has-error @endif">
        <label for="crs_id" class="form-label">Curso <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <select name="crs_id" class="form-control" id="crs_id">
                @foreach($curso as $key => $value)
                    <option value="{{ $key }}" {{ in_array($key, old('crs_id', isset($curso) ? $curso->toArray() : [])) ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
          @if ($errors->has('crs_id')) <p class="help-block">{{ $errors->first('crs_id') }}</p> @endif
      </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('trm_ofc_id')) has-error @endif">
        <label for="trm_ofc_id" class="form-label">Oferta de Curso <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <select class="form-control" name="trm_ofc_id" id="trm_ofc_id">
                <option value="{{$oferta->ofc_id}}">{{$oferta->ofc_ano}} ({{$oferta->modalidade->mdl_nome}})</option>
            </select>
            @if ($errors->has('trm_ofc_id')) <p class="help-block">{{ $errors->first('trm_ofc_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('trm_per_id')) has-error @endif">
        <label for="trm_per_id" class="form-label">Período Letivo <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <select name="trm_per_id" class="form-control">
                @foreach($periodosletivos as $key => $value)
                    <option value="{{ $key }}" {{ old('trm_per_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
            @if ($errors->has('trm_per_id')) <p class="help-block">{{ $errors->first('trm_per_id') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('trm_nome')) has-error @endif">
        <label for="trm_nome" class="form-label">Nome <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="text" name="trm_nome" value="{{ old('trm_nome', $turma->trm_nome ?? '') }}" class="form-control" >
            @if ($errors->has('trm_nome')) <p class="help-block">{{ $errors->first('trm_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('trm_qtd_vagas')) has-error @endif">
        <label for="trm_qtd_vagas" class="form-label">Quantidade de Vagas <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="number" name="trm_qtd_vagas" value="{{ old('trm_qtd_vagas', $turma->trm_qtd_vagas ?? '') }}" class="form-control" >
            @if ($errors->has('trm_qtd_vagas')) <p class="help-block">{{ $errors->first('trm_qtd_vagas') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('trm_integrada')) has-error @endif">
        <label for="trm_integrada" class="form-label">É integrada? <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <select name="trm_integrada" class="form-control">
                @foreach(array(0 => 'Não', 1 => 'Sim') as $key => $value)
                    <option value="{{ $key }}" {{ old('trm_integrada', $turma->trm_integrada ?? 0) == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
            @if ($errors->has('trm_integrada')) <p class="help-block">{{ $errors->first('trm_integrada') }}</p> @endif
        </div>
    </div>
</div>


