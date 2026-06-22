<div class="row">
    <div class="col-md-4">
        <div class="form-group @if($errors->has('lst_nome'))has-error @endif">
            <label for="lst_nome" class="form-label">Nome <small class="obrigatorio-dot">*</small></label>
            <div class="controls">
                <input type="text" name="lst_nome" value="{{ old('lst_nome', $lista->lst_nome ?? '') }}" class="form-control" >
                @if ($errors->has('lst_nome')) <p class="help-block">{{ $errors->first('lst_nome') }}</p> @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group @if($errors->has('lst_descricao'))has-error @endif">
            <label for="lst_descricao" class="form-label">Descricao <small class="obrigatorio-dot">*</small></label>
            <div class="controls">
                <input type="text" name="lst_descricao" value="{{ old('lst_descricao', $lista->lst_descricao ?? '') }}" class="form-control" >
                @if ($errors->has('lst_descricao')) <p class="help-block">{{ $errors->first('lst_descricao') }}</p> @endif
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group @if($errors->has('lst_data_bloqueio'))has-error @endif">
            <label for="lst_data_bloqueio" class="form-label">Data do Bloqueio <small class="obrigatorio-dot">*</small></label>
            <div class="controls">
                <input type="text" name="lst_data_bloqueio" value="{{ old('lst_data_bloqueio', $lista->lst_data_bloqueio ?? '') }}" class="form-control datepicker" >
                @if ($errors->has('lst_data_bloqueio')) <p class="help-block">{{ $errors->first('lst_data_bloqueio') }}</p> @endif
            </div>
        </div>
    </div>
</div>