<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('cursos')) has-error @endif">
        <label for="cursos" class="form-label">Cursos <small class="obrigatorio-dot">*</small></label>
        <div class="controls">
            <select name="cursos[]" class="form-control" multiple="multiple">
                @foreach($cursos as $key => $value)
                    <option value="{{ $key }}" {{ old('cursos[]') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
            @if ($errors->has('cursos')) <p class="help-block">{{ $errors->first('cursos') }}</p> @endif
        </div>
    </div>
</div>
