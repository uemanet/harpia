<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('ltc_titulo')) has-error @endif">
        <label for="ltc_titulo" class="form-label">Título do TCC <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="text" name="ltc_titulo" value="{{ old('ltc_titulo', $lancamentoTcc->ltc_titulo ?? '') }}" class="form-control" >
            @if ($errors->has('ltc_titulo')) <p class="help-block">{{ $errors->first('ltc_titulo') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('ltc_prf_id')) has-error @endif">
        <label for="ltc_prf_id" class="form-label">Professor <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <select name="ltc_prf_id" class="form-control">
                <option value="">Selecione um professor</option>
                @foreach($professores as $key => $value)
                    <option value="{{ $key }}" {{ old('ltc_prf_id', $lancamentoTcc->ltc_prf_id ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
            @if ($errors->has('ltc_prf_id')) <p class="help-block">{{ $errors->first('ltc_prf_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('ltc_tipo')) has-error @endif">
        <label for="ltc_tipo" class="form-label">Tipo de TCC <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <select name="ltc_tipo" class="form-control">
                <option value="">Selecione um tipo</option>
                @foreach($tiposdetcc as $key => $value)
                    <option value="{{ $key }}" {{ old('ltc_tipo', $lancamentoTcc->ltc_tipo ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
            @if ($errors->has('ltc_tipo')) <p class="help-block">{{ $errors->first('ltc_tipo') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('ltc_data_apresentacao')) has-error @endif">
        <label for="ltc_data_apresentacao" class="form-label">Data de apresentação <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="text" name="ltc_data_apresentacao" value="{{ old('ltc_data_apresentacao', $lancamentoTcc->ltc_data_apresentacao ?? '') }}" class="form-control datepicker" data-provide="datepicker" date-date-format="dd/mm/yyyy" >
            @if ($errors->has('ltc_data_apresentacao')) <p class="help-block">{{ $errors->first('ltc_data_apresentacao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('ltc_file')) has-error @endif">
        <label for="ltc_file" class="form-label">Documento</label>
        <div class="controls">
            <input type="file" name="ltc_file" class="form-control file" >
            @if ($errors->has('ltc_file')) <p class="help-block">{{ $errors->first('ltc_file') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('ltc_anx_nome')) has-error @endif">
        <label for="ltc_anx_nome" class="form-label">Anexo</label>
        <div class="input-group">
            @if($anexo != null)
                <input type="text" class="form-control first" placeholder="{{$anexo->anx_nome}}" disabled="">
                <span class="input-group-btn botaoDelete">
                    <button type="button" class="btn btn-danger btn-delete">Excluir</button>
                </span>
            @else
                <input type="text" class="form-control" placeholder="Sem anexos" disabled="">
                <span class="input-group-btn botaoDelete">
                    <button type="button" class="btn btn-danger btn-delete" disabled="">Excluir</button>
                </span>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('ltc_observacao')) has-error @endif">
        <label for="ltc_observacao" class="form-label">Observação</label>
        <div class="controls">
            <textarea name="ltc_observacao" class="form-control" rows="4">{{ old('ltc_observacao') }}</textarea>
            @if ($errors->has('ltc_observacao')) <p class="help-block">{{ $errors->first('ltc_observacao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <input type="hidden" name="ltc_mof_id" value="{{ $lancamentoTcc->ltc_mof_id }}" class="form-control" >
</div>

@section('scripts')
    <script>
        window.PageRoutes = {
            // Add Routes
        };
    </script>

    @vite('modulos/Academico/Resources/js/pages/lancamentostccs/includes/formulario_edit.js')
@stop
