@section('stylesheets')
    @parent
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@stop

<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('prm_nome')) has-error @endif">
        <label for="prm_nome" class="control-label">Nome*</label>
        <div class="controls">
            <input type="text" name="prm_nome" value="{{ old('prm_nome') }}" class="form-control" >
            @if ($errors->has('prm_nome')) <p class="help-block">{{ $errors->first('prm_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('prm_rota')) has-error @endif">
        <label for="prm_rota" class="control-label">Rota*</label>
        <div class="controls">
            <input type="text" name="prm_rota" value="{{ old('prm_rota') }}" class="form-control" >
            @if ($errors->has('prm_rota')) <p class="help-block">{{ $errors->first('prm_rota') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('prm_descricao')) has-error @endif">
        <label for="prm_descricao" class="control-label">Descrição</label>
        <div class="controls">
            <input type="text" name="prm_descricao" value="{{ old('prm_descricao') }}" class="form-control" >
            @if ($errors->has('prm_descricao')) <p class="help-block">{{ $errors->first('prm_descricao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        <button type="submit" class="btn btn-primary pull-right">Salvar dados</button>
    </div>
</div>

@section('scripts')
    @parent
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("select").select2();
        });
    </script>
@stop