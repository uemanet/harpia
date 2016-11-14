<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('crs_id')) has-error @endif">
        {!! Form::label('crs_id', 'Curso*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('crs_id', $cursos, null, ['class' => 'form-control', 'placeholder' => 'Selecione um curso', 'id' => 'crs_id']) !!}
            @if ($errors->has('crs_id')) <p class="help-block">{{ $errors->first('crs_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('mtc_id')) has-error @endif">
        {!! Form::label('mtc_id', 'Matriz Curricular*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('mtc_id', [], null, ['class' => 'form-control', 'id' => 'mtc_id']) !!}
            @if ($errors->has('mtc_id')) <p class="help-block">{{ $errors->first('mtc_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('ofc_id')) has-error @endif">
        {!! Form::label('ofc_id', 'Oferta de Curso*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('ofc_id', [], null, ['class' => 'form-control', 'id' => 'ofc_id']) !!}
            @if ($errors->has('ofc_id')) <p class="help-block">{{ $errors->first('ofc_id') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4 @if ($errors->has('ofd_mdo_id')) has-error @endif">
        {!! Form::label('ofd_mdo_id', 'Modulos da Matriz*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('ofd_mdo_id', [], null, ['class' => 'form-control', 'id' => 'ofd_mdo_id']) !!}
            @if ($errors->has('ofd_mdo_id')) <p class="help-block">{{ $errors->first('ofd_mdo_id') }}</p> @endif
        </div>
    </div>
    <div class="form-group col-md-4 @if ($errors->has('ofd_trm_id')) has-error @endif">
        {!! Form::label('ofd_trm_id', 'Turmas*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('ofd_trm_id', [], null, ['class' => 'form-control', 'id' => 'ofd_trm_id']) !!}
            @if ($errors->has('ofd_trm_id')) <p class="help-block">{{ $errors->first('ofd_trm_id') }}</p> @endif
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
    <div class="form-group col-md-4 @if ($errors->has('ofd_per_id')) has-error @endif">
        {!! Form::label('ofd_per_id', 'Periodo Letivo*', ['class' => 'control-label']) !!}
        <div class="controls">
            {!! Form::select('ofd_per_id', $periodoletivo, null, ['class' => 'form-control', 'placeholder' => 'Selecione um periodo letivo', 'id' => 'ofd_per_id']) !!}
            @if ($errors->has('ofd_per_id')) <p class="help-block">{{ $errors->first('ofd_per_id') }}</p> @endif
        </div>
    </div>
        <div class="form-group col-md-3 @if ($errors->has('ofd_qtd_vagas')) has-error @endif">
            {!! Form::label('ofd_qtd_vagas', 'Vagas*', ['class' => 'control-label']) !!}
            <div class="controls">
                {!! Form::number('ofd_qtd_vagas', old('ofd_qtd_vagas'), ['class' => 'form-control']) !!}
                @if ($errors->has('ofd_qtd_vagas')) <p class="help-block">{{ $errors->first('ofd_qtd_vagas') }}</p> @endif
            </div>
        </div>
</div>
<div class="row">
    <div class="form-group col-md-12">
        {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
    </div>
</div>

@section('scripts')
    @parent

    <script type="application/javascript">
        $(document).ready(function(){
            $('#ofc_crs_id').prop('selectedIndex',0);
        });
    </script>
    <script type="application/javascript">

        $('#ofc_crs_id').change(function() {
            var cursoId = $("#ofc_crs_id").val();

            if (!cursoId) {
                return;
            }

            $.harpia.httpget('{{url('/')}}/academico/async/matrizescurriculares/findallbycurso/' + cursoId).done(function(result){

                $("#mtc_id").empty();

                if ($.isEmptyObject(result)) {
                    $('#mtc_id').append('<option value=#>Sem matrizes curriculares cadastradas</option>');
                } else {
                    $("#mtc_id").append("<option value='' selected>Selecione uma matriz curricular</option>");
                    $.each(result, function(key, value) {
                        $('#mtc_id').append('<option value=' + value.mtc_id + ' >' + value.mtc_titulo + '</option>');
                    });
                }

                $('#mtc_id').focus();
            });
        });

        $('#crs_id').change(function (e) {
            var crsId = $(this).val();

            var selectMatriz = $('#mtc_id');
            var selectOfertas = $('#ofc_id');

            if(crsId) {

                // Populando o select de matrizes
                selectMatriz.empty();
                $.harpia.httpget("{{url('/')}}/academico/async/matrizescurriculares/findallbycurso/" + crsId)
                        .done(function (data) {
                            if(!$.isEmptyObject(data)) {
                                selectMatriz.append("<option>Selecione a matriz</option>");
                                $.each(data, function (key, value) {
                                    selectMatriz.append('<option value="'+value.mtc_id+'">'+value.mtc_titulo+'</option>');
                                });
                            } else {
                                selectMatriz.append("<option>Sem matrizes cadastradas</option>");
                            }
                        });

                // Populando o select de ofertas de cursos
                selectOfertas.empty();
                $.harpia.httpget("{{url('/')}}/academico/async/ofertascursos/findallbycurso/" + crsId)
                        .done(function (data) {
                            if(!$.isEmptyObject(data)) {
                                selectOfertas.append("<option>Selecione a oferta</option>");
                                $.each(data, function (key, value) {
                                    selectOfertas.append('<option value="'+value.ofc_id+'">'+value.ofc_ano+'</option>');
                                });
                            } else {
                                selectOfertas.append("<option>Sem ofertas cadastradas</option>");
                            }
                        });
            }
        });

        $('#mtc_id').change(function (e) {
            var matrizId = $(this).val();

            var selectModulos = $('#ofd_mdo_id');

            if(matrizId) {
                selectModulos.empty();

                $.harpia.httpget('{{url("/")}}/academico/async/modulosmatriz/findallbymatriz/' + matrizId)
                        .done(function (data) {
                            if(!$.isEmptyObject(data)) {
                                selectModulos.append('<option>Selecione o módulo</option>');
                                $.each(data, function (key, obj) {
                                    selectModulos.append('<option value="'+obj.mdo_id+'">'+obj.mdo_nome+'</option>');
                                });
                            } else {
                                selectModulos.append('<option>Sem módulos cadastrados</option>');
                            }
                        });
            }
        });

        $('#ofc_id').change(function (e) {
            var ofertaId = $(this).val();

            var selectTurmas = $('#ofd_trm_id');

            if (ofertaId) {
                selectTurmas.empty();

                $.harpia.httpget('{{url("/")}}/academico/async/turmas/findallbyofertacurso/' + ofertaId)
                        .done(function (data) {
                            if (!$.isEmptyObject(data)){
                                selectTurmas.append('<option>Selecione a turma</option>');
                                $.each(data, function (key, obj) {
                                    selectTurmas.append('<option value="'+obj.trm_id+'">'+obj.trm_nome+'</option>')
                                });
                            }else {
                                selectTurmas.append('<option>Sem turmas cadastradas</option>')
                            }
                        });
            }

        })

        $('#ofd_mdo_id').change(function (e) {
            var moduloId = $(this).val();

            var selectDisciplinas = $('#ofd_mdc_id');

            if (moduloId) {
                selectDisciplinas.empty();

                $.harpia.httpget('{{url("/")}}/academico/async/modulosdisciplinas/getalldisciplinasbymodulo/' + moduloId)
                        .done(function (data) {
                            if (!$.isEmptyObject(data)){
                                selectDisciplinas.append('<option>Selecione a disciplina</option>');
                                $.each(data, function (key, obj) {
                                    selectDisciplinas.append('<option value="'+obj.dis_id+'">'+obj.dis_nome+'</option>')
                                });
                            }else {
                                selectDisciplinas.append('<option>Sem disciplinas cadastradas</option>')
                            }
                        });
            }

        })

    </script>
@stop
