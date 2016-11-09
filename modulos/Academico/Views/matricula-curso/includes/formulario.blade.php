@section('styles')
    <link rel="stylesheet" href="{{url('/')}}/css/plugins/select2.css">
@stop

<div class="row">
    <div class="form-group col-md-3 @if($errors->has('crs_id')) has-error @endif">
        {!! Form::label('crs_id', 'Curso*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('crs_id', $cursos, null , ['class' => 'form-control', 'placeholder' => 'Selecione o curso']) !!}
            @if ($errors->has('crs_id')) <p class="help-block">{{ $errors->first('crs_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if($errors->has('ofc_id')) has-error @endif">
        {!! Form::label('ofc_id', 'Oferta do Curso*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('ofc_id', [], null , ['class' => 'form-control']) !!}
            @if ($errors->has('ofc_id')) <p class="help-block">{{ $errors->first('ofc_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if($errors->has('mat_trm_id')) has-error @endif">
        {!! Form::label('mat_trm_id', 'Turma*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('mat_trm_id', [], null , ['class' => 'form-control grp']) !!}
            @if ($errors->has('mat_trm_id')) <p class="help-block">{{ $errors->first('mat_trm_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-3 @if($errors->has('mat_pol_id')) has-error @endif">
        {!! Form::label('mat_pol_id', 'Polo', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('mat_pol_id', [], null , ['class' => 'form-control grp']) !!}
            @if ($errors->has('mat_pol_id')) <p class="help-block">{{ $errors->first('mat_pol_id') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-3 @if($errors->has('mat_grp_id')) has-error @endif">
        {!! Form::label('mat_grp_id', 'Grupo', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('mat_grp_id', [], null , ['class' => 'form-control']) !!}
            @if ($errors->has('mat_grp_id')) <p class="help-block">{{ $errors->first('mat_grp_id') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados',['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>

@section('scripts')
    <script src="{{url('/')}}/js/plugins/select2.js"></script>
    <script>
        $(function() {
            $('select').select2();

            $('#crs_id').on('change', function (e) {
            var crsId = $(this).val();

            var selectOfertas = $('#ofc_id');

            if(crsId) {
                selectOfertas.empty();

                $.harpia.httpget("{{url('/')}}/academico/async/ofertascursos/findallbycurso/" + crsId)
                    .done(function (data) {
                       if(!$.isEmptyObject(data)) {
                           selectOfertas.append("<option value=''>Selecione a oferta</option>");

                           $.each(data, function (key, obj) {
                              selectOfertas.append('<option value="'+obj.ofc_id+'">'+obj.ofc_ano+'</option>');
                           });
                       }else {
                           selectOfertas.append("<option>Sem ofertas cadastradas</option>");
                       }
                    });
            }
            });

            $('#ofc_id').on('change', function (e) {
                var ofertaId = $(this).val();
                var selectTurmas = $('#mat_trm_id');
                var selectPolos = $('#mat_pol_id');
                var selectGrupos = $('#mat_grp_id');

                if(ofertaId) {
                    selectTurmas.empty();
                    selectPolos.empty();
                    selectGrupos.empty();

                    // Popula select de turmas
                    $.harpia.httpget("{{url('/')}}/academico/async/turmas/findallbyofertacurso/" + ofertaId)
                            .done(function (data) {
                                if(!$.isEmptyObject(data)) {
                                    selectTurmas.append("<option>Selecione a turma</option>");

                                    $.each(data, function (key, obj) {
                                        selectTurmas.append('<option value="'+obj.trm_id+'">'+obj.trm_nome+'</option>');
                                    });
                                }else {
                                    selectTurmas.append("<option>Sem turmas cadastradas</option>");
                                }
                            });

                    // Popula select de polos
                    $.harpia.httpget("{{url('/')}}/academico/async/polos/findallbyofertacurso/" + ofertaId)
                            .done(function (data) {
                                if(!$.isEmptyObject(data)) {
                                    selectPolos.append("<option value=''>Selecione o polo</option>");

                                    $.each(data, function (key, obj) {
                                        selectPolos.append('<option value="'+obj.pol_id+'">'+obj.pol_nome+'</option>');
                                    });
                                }else {
                                    selectPolos.append("<option>Sem polos cadastrados</option>");
                                }
                            });
                }
            });

            $('.grp').on('change', function () {

                var polo = $('#mat_pol_id').val();
                var turma = $('#mat_trm_id').val();

                var selectGrupos = $('#mat_grp_id');

                if(!(turma == '') && !(polo == '')) {
                    selectGrupos.empty();

                    $.harpia.httpget("{{url('/')}}/academico/async/grupos/findallbyturmapolo/" + turma + "/" + polo)
                            .done(function (data) {
                                if(!$.isEmptyObject(data)) {
                                    selectGrupos.append("<option value=''>Selecione o grupo</option>");

                                    $.each(data, function (key, obj) {
                                       selectGrupos.append('<option value="'+obj.grp_id+'">'+obj.grp_nome+'</option>');
                                    });
                                }else {
                                    selectGrupos.append("<option>Sem grupos cadastrados</option>");
                                }
                            });
                }
            });
        });
    </script>
@stop