
    <div class="form-group col-md-4 @if ($errors->has('grp_nome')) has-error @endif">
        <label for="grp_nome" class="form-label">Nome do Grupo <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="text" name="grp_nome" value="{{ old('grp_nome') }}" class="form-control select-control" >
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
