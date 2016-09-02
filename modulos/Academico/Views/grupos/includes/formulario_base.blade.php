<!-- <div class="row"> -->
    <div class="form-group col-md-4 @if ($errors->has('grp_nome')) has-error @endif">
        {!! Form::label('grp_nome', 'Nome do Grupo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::text('grp_nome', old('grp_nome'), ['class' => 'form-control select-control']) !!}
            @if ($errors->has('grp_nome')) <p class="help-block">{{ $errors->first('grp_nome') }}</p> @endif
        </div>
    </div>
<!-- </div> -->
<!-- <div class="row"> -->
    <div class="form-group col-md-2">
        <label class="control-label" style="visibility: hidden">Submit</label>
        <div class="controls">
            {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary']) !!}            
        </div>
    </div>
</div>

@section('scripts')
    <script src="{{asset('/js/plugins/select2.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("select").select2();

            $('#crs_id').on('change', function () {
                var curso = $(this).val();

                if(!curso) {
                    return;
                }

                $.harpia.httpget('{{url('/')}}/academico/async/ofertascursos/findallbycurso/'+curso).done(function(result) {
                    var selectOfertaCursos = $('#ofc_id');

                    selectOfertaCursos.empty();
                    console.log(result);

                    if ($.isEmptyObject(result)) {
                        selectOfertaCursos.append('<option value=#>Sem ofertas disponíveis</option>');
                    } else {
                        selectOfertaCursos.append("<option value='' selected>Selecione uma oferta</option>");
                        $.each(result, function(key, value) {
                            selectOfertaCursos.append('<option value=' + value.ofc_id + ' >' + value.ofc_ano + '</option>');
                        });
                    }

                    selectOfertaCursos.focus();
                });
                
            });

            $('#ofc_id').on('change', function() {
                var oferta = $(this).val();

                if(!oferta) {
                    return;
                }

                //preenche o select de turmas
                $.harpia.httpget('{{url('/')}}/academico/async/turmas/findallbyofertacurso/'+oferta).done(function(result) {
                    var selectTurmas = $('#grp_trm_id');

                    selectTurmas.empty();

                    if ($.isEmptyObject(result)) {
                        selectTurmas.append('<option value=#>Sem turmas disponíveis</option>');
                    } else {
                        selectTurmas.append("<option value='' selected>Selecione uma Turma</option>");
                        $.each(result, function(key, value) {
                            selectTurmas.append('<option value=' + value.trm_id + ' >' + value.trm_nome + '</option>');
                        });
                    }

                    selectTurmas.focus();
                });

                // preenche o select de polos
                $.harpia.httpget('{{url('/')}}/academico/async/polos/findallbyofertacurso/'+oferta).done(function(result) {
                    var selectPolo = $('#grp_pol_id');

                    selectPolo.empty();

                    if ($.isEmptyObject(result)) {
                        selectPolo.append('<option value=#>Sem polos disponíveis</option>');
                    } else {
                        selectPolo.append("<option value='' selected>Selecione um Polo</option>");
                        $.each(result, function(key, value) {
                            selectPolo.append('<option value=' + value.pol_id + ' >' + value.pol_nome + '</option>');
                        });
                    }

                    selectPolo.focus();
                });
            });
        });
    </script>
@endsection