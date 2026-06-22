<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('doc_tpd_id')) has-error @endif">
        <label for="doc_tpd_id" class="form-label">Tipo de Documento*</label>
        <div class="controls">
            <select name="doc_tpd_id" class="form-control">
                <option value="">Selecione um documento</option>
                @foreach($tiposdocumentos as $key => $value)
                    <option value="{{ $key }}" {{ old('doc_tpd_id', $documento->doc_tpd_id ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
            @if ($errors->has('doc_tpd_id')) <p class="help-block">{{ $errors->first('doc_tpd_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('doc_conteudo')) has-error @endif">
        <label for="doc_conteudo" class="form-label">Conteúdo*</label>
        <div class="controls">
            <input type="text" name="doc_conteudo" value="{{ old('doc_conteudo', $documento->doc_conteudo ?? '') }}" class="form-control" >
            @if ($errors->has('doc_conteudo')) <p class="help-block">{{ $errors->first('doc_conteudo') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 @if ($errors->has('doc_orgao')) has-error @endif">
        <label for="doc_orgao" class="form-label">Órgão</label>
        <div class="controls">
            <input type="text" name="doc_orgao" value="{{ old('doc_orgao', $documento->doc_orgao ?? '') }}" class="form-control" >
            @if ($errors->has('doc_orgao')) <p class="help-block">{{ $errors->first('doc_orgao') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 @if ($errors->has('doc_data_expedicao')) has-error @endif">
        <label for="doc_data_expedicao" class="form-label">Data de expedição</label>
        <div class="controls">
            <input type="text" name="doc_data_expedicao" value="{{ old('doc_data_expedicao', $documento->doc_data_expedicao ?? '') }}" class="form-control datepicker" data-provide="datepicker" date-date-format="dd/mm/yyyy" >
            @if ($errors->has('doc_data_expedicao')) <p class="help-block">{{ $errors->first('doc_data_expedicao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('doc_file')) has-error @endif">
        <label for="doc_file" class="form-label">Documento</label>
        <div class="controls">
            <input type="file" name="doc_file" class="form-control file" >
            @if ($errors->has('doc_file')) <p class="help-block">{{ $errors->first('doc_file') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('doc_observacao')) has-error @endif">
        <label for="doc_observacao" class="form-label">Observação</label>
        <div class="controls">
            <textarea name="doc_observacao" class="form-control" rows="4">{{ old('doc_observacao', $documento->doc_observacao ?? '') }}</textarea>
            @if ($errors->has('doc_observacao')) <p class="help-block">{{ $errors->first('doc_observacao') }}</p> @endif
        </div>
    </div>
</div>
<input type="hidden" name="doc_pes_id" value="{{ $pessoa->pes_id }}" class="form-control" >