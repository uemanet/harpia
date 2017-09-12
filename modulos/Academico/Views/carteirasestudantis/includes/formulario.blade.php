<div class="row">
    <div class="col-md-4">
        <div class="form-group @if($errors->has('lst_nome'))has-error @endif">
            {!! Form::label('lst_nome', 'Nome*', ['class' => 'control-label']) !!}
            <div class="controls">
                {!! Form::text('lst_nome', old('lst_nome'), ['class' => 'form-control']) !!}
                @if ($errors->has('lst_nome')) <p class="help-block">{{ $errors->first('lst_nome') }}</p> @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group @if($errors->has('lst_descricao'))has-error @endif">
            {!! Form::label('lst_descricao', 'Descricao*', ['class' => 'control-label']) !!}
            <div class="controls">
                {!! Form::text('lst_descricao', old('lst_descricao'), ['class' => 'form-control']) !!}
                @if ($errors->has('lst_descricao')) <p class="help-block">{{ $errors->first('lst_descricao') }}</p> @endif
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group @if($errors->has('lst_data_bloqueio'))has-error @endif">
            {!! Form::label('lst_data_bloqueio', 'Data do Bloqueio*', ['class' => 'control-label']) !!}
            <div class="controls">
                {!! Form::text('lst_data_bloqueio', old('lst_data_bloqueio'), ['class' => 'form-control']) !!}
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