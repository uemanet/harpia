<div class="row">
    <div class="col-md-4">
        <div class="form-group @if($errors->has('lst_nome'))has-error @endif">
            <label for="lst_nome" class="control-label">Nome*</label>
            <div class="controls">
                <input type="text" name="lst_nome" value="{{ old('lst_nome') }}" class="form-control" >
                @if ($errors->has('lst_nome')) <p class="help-block">{{ $errors->first('lst_nome') }}</p> @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group @if($errors->has('lst_descricao'))has-error @endif">
            <label for="lst_descricao" class="control-label">Descricao*</label>
            <div class="controls">
                <input type="text" name="lst_descricao" value="{{ old('lst_descricao') }}" class="form-control" >
                @if ($errors->has('lst_descricao')) <p class="help-block">{{ $errors->first('lst_descricao') }}</p> @endif
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group @if($errors->has('lst_data_bloqueio'))has-error @endif">
            <label for="lst_data_bloqueio" class="control-label">Data do Bloqueio*</label>
            <div class="controls">
                <input type="text" name="lst_data_bloqueio" value="{{ old('lst_data_bloqueio') }}" class="form-control" >
                @if ($errors->has('lst_data_bloqueio')) <p class="help-block">{{ $errors->first('lst_data_bloqueio') }}</p> @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 form-group">
        <input type="submit" class="btn btn-primary pull-right" value="Salvar Dados">
    </div>
</div>

@section('scripts')
    @parent

    <script src="{{ asset('/js/plugins/input-mask/inputmask.js') }}"></script>
    <script src="{{ asset('/js/plugins/input-mask/inputmask.date.extensions.js') }}"></script>

    <script type="text/javascript">
        Inputmask({"mask": "99/99/9999"}).mask('#lst_data_bloqueio');
    </script>
@stop