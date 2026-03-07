<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('crs_cen_id')) has-error @endif">
        <label for="crs_cen_id" class="control-label">Centro*</label>
        <div class="controls">
            <select name="crs_cen_id" class="form-control">
    <option value="">Selecione um centro</option>
    @foreach($centros as $key => $value)
        <option value="{{ $key }}" {{ old('crs_cen_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
            @if ($errors->has('crs_cen_id')) <p class="help-block">{{ $errors->first('crs_cen_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('crs_nvc_id')) has-error @endif">
        <label for="crs_nvc_id" class="control-label">Nível do Curso*</label>
        <div class="controls">
            <select name="crs_nvc_id" class="form-control">
    <option value="">Selecione um nível</option>
    @foreach($niveiscursos as $key => $value)
        <option value="{{ $key }}" {{ old('crs_nvc_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
            @if ($errors->has('crs_nvc_id')) <p class="help-block">{{ $errors->first('crs_nvc_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('crs_prf_diretor')) has-error @endif">
        <label for="crs_prf_diretor" class="control-label">Diretor do curso*</label>
        <div class="controls">
            <select name="crs_prf_diretor" class="form-control">
    <option value="">Selecione um diretor</option>
    @foreach($professores as $key => $value)
        <option value="{{ $key }}" {{ old('crs_prf_diretor') == $key ? 'selected' : '' }}>{{ $value }}</option>
    @endforeach
</select>
            @if ($errors->has('crs_prf_diretor')) <p class="help-block">{{ $errors->first('crs_prf_diretor') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-8 @if ($errors->has('crs_nome')) has-error @endif">
        <label for="crs_nome" class="control-label">Nome do curso*</label>
        <div class="controls">
            <input type="text" name="crs_nome" value="{{ old('crs_nome') }}" class="form-control" >
            @if ($errors->has('crs_nome')) <p class="help-block">{{ $errors->first('crs_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 @if ($errors->has('crs_sigla')) has-error @endif">
        <label for="crs_sigla" class="control-label">Sigla*</label>
        <div class="controls">
            <input type="text" name="crs_sigla" value="{{ old('crs_sigla') }}" class="form-control" >
            @if ($errors->has('crs_sigla')) <p class="help-block">{{ $errors->first('crs_sigla') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 @if ($errors->has('crs_data_autorizacao')) has-error @endif">
        <label for="crs_data_autorizacao" class="control-label">Data de autorização*</label>
        <div class="controls">
            <input type="text" name="crs_data_autorizacao" value="{{ old('crs_data_autorizacao') }}" class="form-control datepicker" data-provide="datepicker" date-date-format="dd/mm/yyyy" >
            @if ($errors->has('crs_data_autorizacao')) <p class="help-block">{{ $errors->first('crs_data_autorizacao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('crs_descricao')) has-error @endif">
        <label for="crs_descricao" class="control-label">Descrição</label>
        <div class="controls">
            <textarea name="crs_descricao" class="form-control" rows="4">{{ old('crs_descricao') }}</textarea>
            @if ($errors->has('crs_descricao')) <p class="help-block">{{ $errors->first('crs_descricao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('crs_regulamentacao')) has-error @endif">
        <label for="crs_regulamentacao" class="control-label">Regulamentação</label>
        <div class="controls">
            <textarea name="crs_regulamentacao" class="form-control" rows="4">{{ old('crs_regulamentacao') }}</textarea>
            @if ($errors->has('crs_regulamentacao')) <p class="help-block">{{ $errors->first('crs_regulamentacao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('crs_resolucao')) has-error @endif">
        <label for="crs_resolucao" class="control-label">Resolução</label>
        <div class="controls">
            <input type="text" name="crs_resolucao" value="{{ old('crs_resolucao') }}" class="form-control" >
            @if ($errors->has('crs_resolucao')) <p class="help-block">{{ $errors->first('crs_resolucao') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('crs_autorizacao')) has-error @endif">
        <label for="crs_autorizacao" class="control-label">Autorização</label>
        <div class="controls">
            <input type="text" name="crs_autorizacao" value="{{ old('crs_autorizacao') }}" class="form-control" >
            @if ($errors->has('crs_autorizacao')) <p class="help-block">{{ $errors->first('crs_autorizacao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('crs_eixo')) has-error @endif">
        <label for="crs_eixo" class="control-label">Eixo</label>
        <div class="controls">
            <input type="text" name="crs_eixo" value="{{ old('crs_eixo') }}" class="form-control" >
            @if ($errors->has('crs_eixo')) <p class="help-block">{{ $errors->first('crs_eixo') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('crs_habilitacao')) has-error @endif">
        <label for="crs_habilitacao" class="control-label">Habilitação</label>
        <div class="controls">
            <input type="text" name="crs_habilitacao" value="{{ old('crs_habilitacao') }}" class="form-control" >
            @if ($errors->has('crs_habilitacao')) <p class="help-block">{{ $errors->first('crs_habilitacao') }}</p> @endif
        </div>
    </div>
</div>
@include('Academico::cursos.includes.formulario_configuracoes')
<div class="row">
    <div class="form-group col-md-12">
        <button type="submit" class="btn btn-primary pull-right">Salvar dados</button>
    </div>
</div>
