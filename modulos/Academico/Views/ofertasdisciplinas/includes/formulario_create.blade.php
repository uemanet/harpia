<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('mtc_id')) has-error @endif">
        {!! Form::label('mtc_id', 'Matriz Curricular*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('mtc_id', [], null, ['class' => 'form-control', 'id' => 'mtc_id']) !!}
            @if ($errors->has('mtc_id')) <p class="help-block">{{ $errors->first('mtc_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('ofd_mdo_id')) has-error @endif">
        {!! Form::label('ofd_mdo_id', 'Modulos da Matriz*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('ofd_mdo_id', [], null, ['class' => 'form-control', 'id' => 'ofd_mdo_id']) !!}
            @if ($errors->has('ofd_mdo_id')) <p class="help-block">{{ $errors->first('ofd_mdo_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('ofd_mdc_id')) has-error @endif">
        {!! Form::label('ofd_mdc_id', 'Disciplinas*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('ofd_mdc_id', [], null, ['class' => 'form-control', 'id' => 'ofd_mdc_id']) !!}
            @if ($errors->has('ofd_mdc_id')) <p class="help-block">{{ $errors->first('ofd_mdc_id') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('ofd_prf_id')) has-error @endif">
        {!! Form::label('ofd_prf_id', 'Professor*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('ofd_prf_id', $professor, null, ['class' => 'form-control', 'placeholder' => 'Selecione um professor', 'id' => 'ofd_prf_id']) !!}
            @if ($errors->has('ofd_prf_id')) <p class="help-block">{{ $errors->first('ofd_prf_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-2 @if ($errors->has('ofd_qtd_vagas')) has-error @endif">
        {!! Form::label('ofd_qtd_vagas', 'Vagas*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::number('ofd_qtd_vagas', old('ofd_qtd_vagas'), ['class' => 'form-control']) !!}
            @if ($errors->has('ofd_qtd_vagas')) <p class="help-block">{{ $errors->first('ofd_qtd_vagas') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Adicionar Disciplina', ['class' => 'btn btn-primary pull-right', 'id' => 'btnAdicionar']) !!}
    </div>
</div>

@section('scripts')
    @parent
    <script type="application/javascript">

        var selectMatriz = $('#mtc_id');
        var selectOfertas = $('#ofc_id');
        var selectTurmas = $('#ofd_trm_id');
        var selectPeriodos = $('#ofd_per_id');
        var selectModulos = $('#ofd_mdo_id');
        var selectDisciplinas = $('#ofd_mdc_id');

        // populando select de matrizes e ofertas de cursos
        $('#crs_id').change(function (e) {
            var crsId = $(this).val();

            if(crsId) {

                // limpando todos os selects
                selectMatriz.empty();
                selectOfertas.empty();
                selectTurmas.empty();
                selectPeriodos.empty();
                selectModulos.empty();

                // Populando o select de matrizes
                $.harpia.httpget("{{url('/')}}/academico/async/matrizescurriculares/findallbycurso/" + crsId)
                        .done(function (data) {
                            if(!$.isEmptyObject(data)) {
                                selectMatriz.append("<option value=''>Selecione a matriz</option>");
                                $.each(data, function (key, value) {
                                    selectMatriz.append('<option value="'+value.mtc_id+'">'+value.mtc_titulo+'</option>');
                                });
                            } else {
                                selectMatriz.append("<option value=''>Sem matrizes cadastradas</option>");
                            }
                        });

                // Populando o select de ofertas de cursos
                $.harpia.httpget("{{url('/')}}/academico/async/ofertascursos/findallbycurso/" + crsId)
                        .done(function (data) {
                            if(!$.isEmptyObject(data)) {
                                selectOfertas.append("<option value=''>Selecione a oferta</option>");
                                $.each(data, function (key, value) {
                                    selectOfertas.append('<option value="'+value.ofc_id+'">'+value.ofc_ano+'</option>');
                                });
                            } else {
                                selectOfertas.append("<option value=''>Sem ofertas cadastradas</option>");
                            }
                        });
            }
        });

        // populando select de turmas
        $('#ofc_id').change(function (e) {
            var ofertaId = $(this).val();

            if (ofertaId) {
                // limpando selects
                selectTurmas.empty();
                selectPeriodos.empty();

                $.harpia.httpget('{{url("/")}}/academico/async/turmas/findallbyofertacurso/' + ofertaId)
                        .done(function (data) {
                            if (!$.isEmptyObject(data)){
                                selectTurmas.append('<option value="">Selecione a turma</option>');
                                $.each(data, function (key, obj) {
                                    selectTurmas.append('<option value="'+obj.trm_id+'">'+obj.trm_nome+'</option>')
                                });
                            }else {
                                selectTurmas.append('<option value="">Sem turmas cadastradas</option>')
                            }
                        });
            }

        });

        // populando select de periodos letivos
        selectTurmas.change(function() {
            var turmaId = $(this).val();

            if(turmaId) {
                // limpando selects
                selectPeriodos.empty();
                $.harpia.httpget("{{url('/')}}/academico/async/periodosletivos/findallbyturma/"+turmaId)
                .done(function(response) {
                    if(!$.isEmptyObject(response))
                    {
                        selectPeriodos.append("<option value=''>Selecione um periodo</option>");
                        $.each(response, function (key, obj) {
                            selectPeriodos.append("<option value='"+obj.per_id+"'>"+obj.per_nome+"</option>");
                        });
                    } else {
                        selectPeriodos.append("<option value=''>Sem períodos disponíveis</option>");
                    }
                });
            }
        });

        $('#mtc_id').change(function (e) {
            var matrizId = $(this).val();

            if(matrizId) {
                selectModulos.empty();
                selectDisciplinas.empty();

                $.harpia.httpget('{{url("/")}}/academico/async/modulosmatriz/findallbymatriz/' + matrizId)
                        .done(function (data) {
                            if(!$.isEmptyObject(data)) {
                                selectModulos.append('<option value="">Selecione o módulo</option>');
                                $.each(data, function (key, obj) {
                                    selectModulos.append('<option value="'+obj.mdo_id+'">'+obj.mdo_nome+'</option>');
                                });
                            } else {
                                selectModulos.append('<option value="">Sem módulos cadastrados</option>');
                            }
                        });
            }
        });

        $('#ofd_mdo_id').change(function (e) {
            var moduloId = $(this).val();
            var turmaId = $('#ofd_trm_id').val();
            var periodoId = $('#ofd_per_id').val();

            if (moduloId) {
                selectDisciplinas.empty();

                $.harpia.httpget('{{url("/")}}/academico/async/modulosdisciplinas/getdisciplinasnotofertadasbymodulo/'+moduloId+'/'+turmaId+'/'+periodoId)
                        .done(function (data) {
                            if (!$.isEmptyObject(data)){
                                selectDisciplinas.append('<option value="">Selecione a disciplina</option>');
                                $.each(data, function (key, obj) {
                                    selectDisciplinas.append('<option value="'+obj.mdc_id+'">'+obj.dis_nome+'</option>')
                                });
                            }else {
                                selectDisciplinas.append('<option value="">Sem disciplinas para ofertar</option>')
                            }
                        });
            }

        });

    </script>
@stop
