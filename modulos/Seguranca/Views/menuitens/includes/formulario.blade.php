@section('stylesheets')
    @parent
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@stop

<div class="row">
    <div class="col-md-2">
        <div class="form-group @if($errors->has('mit_mod_id'))has-error @endif">
            {!! Form::label('mit_mod_id', 'Módulo*', ['class' => 'control-label']) !!}
            {!! Form::select('mit_mod_id', $modulos, old('mit_mod_id'), ['class' => 'form-control', 'placeholder' => 'Selecione o módulo']) !!}
            @if ($errors->has('mit_mod_id')) <p class="help-block">{{ $errors->first('mit_mod_id') }}</p> @endif
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group @if($errors->has('mit_item_pai'))has-error @endif">
            {!! Form::label('mit_item_pai', 'Item Pai', ['class' => 'control-label']) !!}
            {!! Form::select('mit_item_pai', $itens, old('mit_item_pai'), ['class' => 'form-control', 'placeholder' => 'Selecione um item']) !!}
            @if ($errors->has('mit_item_pai')) <p class="help-block">{{ $errors->first('mit_item_pai') }}</p> @endif
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group @if($errors->has('mit_nome'))has-error @endif">
            {!! Form::label('mit_nome', 'Nome*', ['class' => 'control-label']) !!}
            {!! Form::text('mit_nome', old('mit_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('mit_nome')) <p class="help-block">{{ $errors->first('mit_nome') }}</p> @endif
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group @if($errors->has('mit_icone'))has-error @endif">
            {!! Form::label('mit_icone', 'Icone*', ['class' => 'control-label']) !!}
            {!! Form::text('mit_icone', old('mit_icone'), ['class' => 'form-control']) !!}
            @if ($errors->has('mit_icone')) <p class="help-block">{{ $errors->first('mit_icone') }}</p> @endif
        </div>
    </div>
    <div class="col-md-1">
        <label class="control-label"></label>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="mit_visivel" value="1" @if(isset($itemMenu) && $itemMenu->mit_visivel) checked @endif> <span style="font-weight: 700;">Visível</span>
            </label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <div class="form-group @if($errors->has('mit_rota'))has-error @endif">
                {!! Form::label('mit_rota', 'Rota', ['class' => 'control-label']) !!}
                {!! Form::text('mit_rota', old('mit_rota'), ['class' => 'form-control']) !!}
                @if ($errors->has('mit_rota')) <p class="help-block">{{ $errors->first('mit_rota') }}</p> @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <div class="form-group @if($errors->has('mit_descricao'))has-error @endif">
                {!! Form::label('mit_descricao', 'Descrição', ['class' => 'control-label']) !!}
                {!! Form::text('mit_descricao', old('mit_descricao'), ['class' => 'form-control']) !!}
                @if ($errors->has('mit_descricao')) <p class="help-block">{{ $errors->first('mit_descricao') }}</p> @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <button type="submit" class="btn btn-primary pull-right">Salvar Item</button>
    </div>
</div>

@section('scripts')
    @parent

    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        $(function() {
            $('select').select2();

            $('#mit_mod_id').change(function() {

                var modulo = $(this).val();

                $('#mit_item_pai').empty();

                $.harpia.httpget("{{url('/')}}/seguranca/async/menuitens/getitenbymodulo/"+modulo).done(function(data) {
                    $('#mit_item_pai').append('<option value="">Selecione um item</option>');
                    if (!$.isEmptyObject(data)) {
                        $.each(data, function(key, value) {
                            $('#mit_item_pai').append('<option value="'+key+'">'+value+'</option>');
                        });
                    } else {
                        $('#mit_item_pai').append('<option value="">Não há itens cadastrados</option>');
                    }
                });
            });
        });
    </script>
@stop