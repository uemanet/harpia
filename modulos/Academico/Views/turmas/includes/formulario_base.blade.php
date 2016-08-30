<div class="row">
    <div class="form-group col-md-6 @if ($errors->has('trm_nome')) has-error @endif">
        {!! Form::label('trm_nome', 'Nome*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('trm_nome', old('trm_nome'), ['class' => 'form-control']) !!}
            @if ($errors->has('trm_nome')) <p class="help-block">{{ $errors->first('trm_nome') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-6 @if ($errors->has('trm_qtd_vagas')) has-error @endif">
        {!! Form::label('trm_qtd_vagas', 'Quantidade de Vagas*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::number('trm_qtd_vagas', old('trm_qtd_vagas'), ['class' => 'form-control']) !!}
            @if ($errors->has('trm_qtd_vagas')) <p class="help-block">{{ $errors->first('trm_qtd_vagas') }}</p> @endif
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

        $('#crs_id').change(function() {
            var cursoId = $("#crs_id").val();

            if (!cursoId) {
                return;
            }

            $.harpia.httpget('{{url('/')}}/academico/async/ofertascursos/findallbycurso/' + cursoId).done(function(result){

                $("#trm_ofc_id").empty();

                if ($.isEmptyObject(result)) {
                    $('#trm_ofc_id').append('<option value=#>Sem ofertas de curso cadastradas</option>');
                } else {
                    $("#trm_ofc_id").append("<option value='' selected>Selecione uma oferta de curso</option>");
                    $.each(result, function(key, value) {
                        $('#trm_ofc_id').append('<option value=' + value.ofc_id + ' >' + value.ofc_ano + '</option>');
                    });
                }

                $('#trm_ofc_id').focus();
            });
        });

    </script>
@stop
