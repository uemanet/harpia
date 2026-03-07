<div class="row">
    <div class="col-md-12">
        <h4>Configurações do Curso</h4>
    </div>
</div>
<hr>
@if(Route::getCurrentRoute()->getName() == 'academico.cursos.edit')
    <div class="row">
        <div class="col-md-12">
            <div class="callout callout-warning">
                <h4>Atenção</h4>
                <p>Alterar as configurações de notas do curso exige que a migração das notas das turmas relativas ao curso seja refeita manualmente.</p>
            </div>
        </div>
    </div>
@endif
<div class="row">
    <div class="col-md-4 form-group @if ($errors->has('media_min_aprovacao')) has-error @endif">
        <label for="media_min_aprovacao" class="control-label">Média Mínima Para Aprovação*</label>
        <div class="controls">
            <input type="number" name="media_min_aprovacao" value="{{ old('media_min_aprovacao') }}" class="form-control" min="1.0" max="10.0" step="0.1" >
            @if ($errors->has('media_min_aprovacao')) <p class="help-block">{{ $errors->first('media_min_aprovacao') }}</p> @endif
        </div>
    </div>
    <div class="col-md-4 form-group @if ($errors->has('media_min_final')) has-error @endif">
        <label for="media_min_final" class="control-label">Média Mínima para ir à Prova Final*</label>
        <div class="controls">
            <input type="number" name="media_min_final" value="{{ old('media_min_final') }}" class="form-control" min="1.0" max="10.0" step="0.1" >
            @if ($errors->has('media_min_final')) <p class="help-block">{{ $errors->first('media_min_final') }}</p> @endif
        </div>
    </div>
    <div class="col-md-4 form-group @if ($errors->has('media_min_aprovacao_final')) has-error @endif">
        <label for="media_min_aprovacao_final" class="control-label">Média Mínima para aprovação na Prova Final*</label>
        <div class="controls">
            <input type="number" name="media_min_aprovacao_final" value="{{ old('media_min_aprovacao_final') }}" class="form-control" min="1.0" max="10.0" step="0.1" >
            @if ($errors->has('media_min_aprovacao_final')) <p class="help-block">{{ $errors->first('media_min_aprovacao_final') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 form-group @if ($errors->has('modo_recuperacao')) has-error @endif">
        <label for="modo_recuperacao" class="control-label">Modo de Recuperação*</label>
        <div class="controls">
            <select name="modo_recuperacao" class="form-control">
    <option value="">Selecione o modo</option>
    <option value="substituir_menor_nota" {{ old('modo_recuperacao') == 'substituir_menor_nota' ? 'selected' : '' }}>Substituir Menor Nota</option>
    <option value="substituir_media_final" {{ old('modo_recuperacao') == 'substituir_media_final' ? 'selected' : '' }}>Substituir Média Final</option>
</select>
            @if ($errors->has('modo_recuperacao')) <p class="help-block">{{ $errors->first('modo_recuperacao') }}</p> @endif
        </div>
    </div>
    <div class="col-md-6 form-group @if ($errors->has('conceitos_aprovacao')) has-error @endif">
        <label for="conceitos_aprovacao" class="control-label">Conceitos para Aprovação*</label>
        <div class="controls">
            <select name="conceitos_aprovacao[]" class="form-control" multiple="multiple">
    <option value="Insuficiente" {{ old('conceitos_aprovacao[]') == 'Insuficiente' ? 'selected' : '' }}>Insuficiente</option>
    <option value="Regular" {{ old('conceitos_aprovacao[]') == 'Regular' ? 'selected' : '' }}>Regular</option>
    <option value="Bom" {{ old('conceitos_aprovacao[]') == 'Bom' ? 'selected' : '' }}>Bom</option>
    <option value="Muito Bom" {{ old('conceitos_aprovacao[]') == 'Muito Bom' ? 'selected' : '' }}>Muito Bom</option>
    <option value="Excelente" {{ old('conceitos_aprovacao[]') == 'Excelente' ? 'selected' : '' }}>Excelente</option>
</select>
            @if ($errors->has('conceitos_aprovacao')) <p class="help-block">{{ $errors->first('conceitos_aprovacao') }}</p> @endif
        </div>
    </div>
</div>