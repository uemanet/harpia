<div class="row">
    <div class="col-md-3">
        <div class="@if($errors->has('mit_mod_id'))has-error @endif">
            {!! Form::label('mit_mod_id', 'Módulo*', ['class' => 'form-label']) !!}
            {!! Form::select('mit_mod_id', $modulos, old('mit_mod_id'), ['class' => 'form-control', 'placeholder' => 'Selecione o módulo']) !!}
            @if ($errors->has('mit_mod_id')) <p class="help-block">{{ $errors->first('mit_mod_id') }}</p> @endif
        </div>
    </div>
    <div class="col-md-3">
        <div class="@if($errors->has('mit_item_pai'))has-error @endif">
            {!! Form::label('mit_item_pai', 'Item Pai', ['class' => 'form-label']) !!}
            {!! Form::select('mit_item_pai', $itens, old('mit_item_pai'), ['class' => 'form-control', 'placeholder' => 'Selecione um item']) !!}
            @if ($errors->has('mit_item_pai')) <p class="help-block">{{ $errors->first('mit_item_pai') }}</p> @endif
        </div>
    </div>
    <div class="col-md-3">
        <div class="@if($errors->has('mit_nome'))has-error @endif">
            {!! Form::label('mit_nome', 'Nome*', ['class' => 'form-label']) !!}
            {!! Form::text('mit_nome', old('mit_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('mit_nome')) <p class="help-block">{{ $errors->first('mit_nome') }}</p> @endif
        </div>
    </div>
    <div class="col-md-3">
        <div class="@if($errors->has('mit_icone'))has-error @endif">
            {!! Form::label('mit_icone', 'Icone*', ['class' => 'form-label']) !!}
            {!! Form::text('mit_icone', old('mit_icone'), ['class' => 'form-control']) !!}
            @if ($errors->has('mit_icone')) <p class="help-block">{{ $errors->first('mit_icone') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-5">
        <div class="form-group">
            <div class="@if($errors->has('mit_rota'))has-error @endif">
                {!! Form::label('mit_rota', 'Rota', ['class' => 'form-label']) !!}
                {!! Form::text('mit_rota', old('mit_rota'), ['class' => 'form-control']) !!}
                @if ($errors->has('mit_rota')) <p class="help-block">{{ $errors->first('mit_rota') }}</p> @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <div class="@if($errors->has('mit_descricao'))has-error @endif">
                {!! Form::label('mit_descricao', 'Descrição', ['class' => 'form-label']) !!}
                {!! Form::text('mit_descricao', old('mit_descricao'), ['class' => 'form-control']) !!}
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
    @parent
    <script type="text/javascript">
        $(function() {
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