<div class="row">
    <div class="form-group col-md-3 @if ($errors->has('prm_nome')) has-error @endif">
        {!! Form::label('prm_nome', 'Nome da permissão*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('prm_nome', old('prm_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('prm_nome')) <p class="help-block">{{ $errors->first('prm_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-9 @if ($errors->has('prm_descricao')) has-error @endif">
        {!! Form::label('prm_descricao', 'Descrição da permissão', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('prm_descricao', old('prm_descricao'), ['class' => 'form-control']) !!}
            @if ($errors->has('prm_descricao')) <p class="help-block">{{ $errors->first('prm_descricao') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>

@section('scripts')
    <script type="application/javascript">
        $('#mod_id').change(function() {
            var moduloId = $("#mod_id").val();

            if (!moduloId) {
                return;
            }

            $.harpia.httpget('{{url('/')}}/seguranca/ajax/recursos/findallbymodulo/' + moduloId).done(function(result){

                $("#prm_rcs_id").empty();

                if ($.isEmptyObject(result)) {
                    $('#prm_rcs_id').append('<option value=#>Sem recursos disponíveis</option>');
                } else {
                    $("#prm_rcs_id").append("<option value='' selected>Selecione um recurso</option>");
                    $.each(result, function(key, value) {
                        $('#prm_rcs_id').append('<option value=' + value.rcs_id + ' >' + value.rcs_nome + '</option>');
                    });
                }

                $('#prm_rcs_id').focus();
            });
        });

    </script>
@stop