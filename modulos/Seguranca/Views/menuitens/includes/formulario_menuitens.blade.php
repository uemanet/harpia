<div class="row">
    <div class="col-md-2">
        <div class="form-group @if($errors->has('mit_mod_id'))has-error @endif">
            <label for="mit_mod_id" class="form-label">Módulo*</label>
            <select name="mit_mod_id" class="form-control">
                <option value="">Selecione o módulo</option>
                @foreach($modulos as $key => $value)
                    <option value="{{ $key }}" {{ old('mit_mod_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
            @if ($errors->has('mit_mod_id')) <p class="help-block">{{ $errors->first('mit_mod_id') }}</p> @endif
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group @if($errors->has('mit_item_pai'))has-error @endif">
            <label for="mit_item_pai" class="form-label">Item Pai</label>
            <select name="mit_item_pai" class="form-control">
                <option value="">Selecione um item</option>
                @foreach($itens as $key => $value)
                    <option value="{{ $key }}" {{ old('mit_item_pai') == $key ? 'selected' : '' }}>{{ $value }}</option>
                @endforeach
            </select>
            @if ($errors->has('mit_item_pai')) <p class="help-block">{{ $errors->first('mit_item_pai') }}</p> @endif
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group @if($errors->has('mit_nome'))has-error @endif">
            <label for="mit_nome" class="form-label">Nome*</label>
            <input type="text" name="mit_nome" value="{{ old('mit_nome') }}" class="form-control" >
            @if ($errors->has('mit_nome')) <p class="help-block">{{ $errors->first('mit_nome') }}</p> @endif
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group @if($errors->has('mit_icone'))has-error @endif">
            <label for="mit_icone" class="form-label">Icone*</label>
            <input type="text" name="mit_icone" value="{{ old('mit_icone') }}" class="form-control" >
            @if ($errors->has('mit_icone')) <p class="help-block">{{ $errors->first('mit_icone') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-5">
        <div class="form-group">
            <div class="form-group @if($errors->has('mit_rota'))has-error @endif">
                <label for="mit_rota" class="form-label">Rota</label>
                <input type="text" name="mit_rota" value="{{ old('mit_rota') }}" class="form-control" >
                @if ($errors->has('mit_rota')) <p class="help-block">{{ $errors->first('mit_rota') }}</p> @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <div class="form-group @if($errors->has('mit_descricao'))has-error @endif">
                <label for="mit_descricao" class="form-label">Descrição</label>
                <input type="text" name="mit_descricao" value="{{ old('mit_descricao') }}" class="form-control" >
                @if ($errors->has('mit_descricao')) <p class="help-block">{{ $errors->first('mit_descricao') }}</p> @endif
            </div>
        </div>
    </div>
    <div class="col-md-1">
        <label class="form-label"></label>
        <div class="form-check">
            <label>
                <input type="checkbox" name="mit_visivel" value="1" class="form-check-input" @if(isset($itemMenu) && $itemMenu->mit_visivel) checked @endif>
                <label class="form-check-label" style="font-weight: 700;">Visível</label>
            </label>
        </div>
    </div>
</div>

@section('scripts')
    @vite('modulos/Seguranca/Resources/js/pages/menuitens/formulario_menuitens.js')
@stop