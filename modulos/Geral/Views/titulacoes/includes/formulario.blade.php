<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('tit_nome')) has-error @endif">
        <label for="tit_nome" class="control-label">Nome da titulação*</label>
        <div class="controls">
            <input type="text" name="tit_nome" value="{{ old('tit_nome') }}" class="form-control" >
            @if ($errors->has('tit_nome')) <p class="help-block">{{ $errors->first('tit_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('tit_peso')) has-error @endif">
        <label for="tit_peso" class="control-label">Peso*</label>
        <div class="controls">
            <input type="number" name="tit_peso" value="{{ old('tit_peso') }}" class="form-control" >
            @if ($errors->has('tit_peso')) <p class="help-block">{{ $errors->first('tit_peso') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12 @if ($errors->has('tit_descricao')) has-error @endif">
        <label for="tit_descricao" class="control-label">Descrição</label>
        <div class="controls">
            <textarea name="tit_descricao" class="form-control">{{ old('tit_descricao') }}</textarea>
            @if ($errors->has('tit_descricao')) <p class="help-block">{{ $errors->first('tit_descricao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <button type="submit" class="btn btn-primary pull-right">Salvar dados</button>
    </div>
</div>