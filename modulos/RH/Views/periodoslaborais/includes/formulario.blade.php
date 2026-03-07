<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('pel_inicio')) has-error @endif">
        <label for="pel_inicio" class="control-label">Início*</label>
        <div class="controls">
            <input type="text" name="pel_inicio" value="{{ old('pel_inicio') }}" class="form-control datepicker" data-provide="datepicker" date-date-format="dd/mm/yyyy" >
            @if ($errors->has('pel_inicio')) <p class="help-block">{{ $errors->first('pel_inicio') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('pel_termino')) has-error @endif">
        <label for="pel_termino" class="control-label">Término*</label>
        <div class="controls">
            <input type="text" name="pel_termino" value="{{ old('pel_termino') }}" class="form-control datepicker" data-provide="datepicker" date-date-format="dd/mm/yyyy" >
            @if ($errors->has('pel_termino')) <p class="help-block">{{ $errors->first('pel_termino') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <button type="submit" class="btn btn-primary pull-right">Salvar dados</button>
    </div>
</div>