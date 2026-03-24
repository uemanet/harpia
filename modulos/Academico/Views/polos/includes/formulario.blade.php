<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('pol_nome')) has-error @endif">
        <label for="pol_nome" class="form-label">Nome do polo <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <input type="text" name="pol_nome" value="{{ old('pol_nome', $polo->pol_nome ?? '') }}" class="form-control" >
            @if ($errors->has('pol_nome')) <p class="help-block">{{ $errors->first('pol_nome') }}</p> @endif
        </div>
    </div>
</div>