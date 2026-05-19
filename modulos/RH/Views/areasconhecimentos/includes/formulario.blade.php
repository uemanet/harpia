<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('arc_descricao')) has-error @endif">
        <label for="arc_descricao" class="control-label">Nome da área de conhecimento*</label>
        <div class="controls">
            <input type="text" name="arc_descricao" value="{{ old('arc_descricao', $areaConhecimento->arc_descricao) }}" class="form-control" >
            @if ($errors->has('arc_descricao')) <p class="help-block">{{ $errors->first('arc_descricao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <button type="submit" class="btn btn-primary float-end">Salvar dados</button>
    </div>
</div>