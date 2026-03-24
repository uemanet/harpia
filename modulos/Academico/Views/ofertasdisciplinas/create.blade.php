@extends('layouts.modulos.default')

@section('title')
    Ofertas de Disciplinas
@stop

@section('subtitle')
    Cadastro de oferta de disciplina
@stop

@section('content')
    <div class="row py-2">
        <div class="card card-primary card-outline">
            <div class="card-header with-border">
                <h3 class="card-title"><i class="fa fa-filter"></i> Filtrar dados</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="crs_id" class="form-label">Curso <small class="obrigatorio-dot">*</small></label>
                        <select name="crs_id" id="crs_id" class="form-control select2">
                            <option value="">Escolha um curso</option>
                            @foreach($cursos as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="ofc_id" class="form-label">Oferta do Curso <small class="obrigatorio-dot">*</small></label>
                        <select name="ofc_id" id="ofc_id" class="form-control select2"></select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="mtc_id" class="form-label">Matriz Curricular <small class="obrigatorio-dot">*</small></label>
                        <select name="mtc_id" id="mtc_id" class="form-control select2"></select>
                    </div>
                </div>
                <div class="row py-1">
                    <div class="form-group col-md-4">
                        <label for="ofd_mdo_id" class="form-label">Módulos da Matriz Curricular <small class="obrigatorio-dot">*</small></label>
                        <select name="ofd_mdo_id" id="ofd_mdo_id" class="form-control select2"></select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="ofd_trm_id" class="form-label">Turma <small class="obrigatorio-dot">*</small></label>
                        <select name="ofd_trm_id" id="ofd_trm_id" class="form-control select2"></select>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="ofd_per_id" class="form-label">Período Letivo <small class="obrigatorio-dot">*</small></label>
                        <select name="ofd_per_id" id="ofd_per_id" class="form-control select2"></select>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="" class="form-label">&nbsp;</label>
                        <button class="btn btn-primary w-100" id="btnLocalizar">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>

    <div class="row py-2" id="table-disciplinas"></div>

    <div class="row py-2" id="table-ofertas"></div>
@stop

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            var token = "{{csrf_token()}}";

            var selectCursos = $('#crs_id');
            var selectOfertasCursos = $('#ofc_id');
            var selectTurmas = $('#ofd_trm_id');
            var selectMatrizCurricular = $('#mtc_id');
            var selectModulosMatriz = $('#ofd_mdo_id');
            var selectPeriodosLetivos = $('#ofd_per_id');

            // populando select de matrizes e ofertas de cursos
            selectCursos.change(function (e) {
                var crsId = $(this).val();

                if(crsId) {

                    // limpando todos os selects
                    selectOfertasCursos.empty();
                    selectMatrizCurricular.empty();
                    selectTurmas.empty();
                    selectModulosMatriz.empty();
                    selectPeriodosLetivos.empty();

                    // Populando o select de ofertas de cursos
                    $.harpia.httpget("{{url('/')}}/academico/async/ofertascursos/findallbycurso/" + crsId)
                    .done(function (data) {
                        if(!$.isEmptyObject(data)) {
                            selectOfertasCursos.append("<option value=''>Selecione a oferta</option>");
                            $.each(data, function (key, value) {
                                selectOfertasCursos.append('<option value="'+value.ofc_id+'">'+value.ofc_ano+' ('+value.mdl_nome+')</option>');
                            });
                        } else {
                            selectOfertasCursos.append("<option value=''>Sem ofertas cadastradas</option>");
                        }
                    });
                }
            });

            // populando select de turmas
            selectOfertasCursos.change(function (e) {
                var ofertaId = $(this).val();

                if (ofertaId) {
                    // limpando selects
                    selectMatrizCurricular.empty();
                    selectTurmas.empty();
                    selectModulosMatriz.empty();
                    selectPeriodosLetivos.empty();

                    // populando o select de matriz curricular
                    $.harpia.httpget('{{url("/")}}/academico/async/matrizescurriculares/findbyofertacurso/' + ofertaId)
                    .done(function (response) {
                        var mtc_id = '';
                        if (!$.isEmptyObject(response)) {
                            mtc_id = response.mtc_id;
                            selectMatrizCurricular.append('<option value="'+response.mtc_id+'">'+response.mtc_titulo+'</option>')
                        } else {
                            selectMatrizCurricular.append('<option value="">Sem matriz cadastrada</option>')
                        }

                        // populando o select de modulos da matriz curricular
                        $.harpia.httpget('{{url("/")}}/academico/async/modulosmatriz/findallbymatriz/' + mtc_id)
                        .done(function (data) {
                            if(!$.isEmptyObject(data)) {
                                selectModulosMatriz.append('<option value="">Selecione o módulo</option>');
                                $.each(data, function (key, obj) {
                                    var option = '<option value="'+obj.mdo_id+'"';
                                    if (key == 0) {
                                        option += ' selected';
                                    }
                                    option += '>'+obj.mdo_nome+'</option>';
                                    selectModulosMatriz.append(option);
                                });
                            } else {
                                selectModulosMatriz.append('<option value="">Sem módulos cadastrados</option>');
                            }
                        });
                    });

                    // populando o select de turmas
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
                    selectPeriodosLetivos.empty();
                    $.harpia.httpget("{{url('/')}}/academico/async/periodosletivos/findallbyturma/"+turmaId)
                    .done(function(response) {
                        if(!$.isEmptyObject(response))
                        {
                            selectPeriodosLetivos.append("<option value=''>Selecione um periodo</option>");
                            $.each(response, function (key, obj) {
                                selectPeriodosLetivos.append("<option value='"+obj.per_id+"'>"+obj.per_nome+"</option>");
                            });
                        } else {
                            selectPeriodosLetivos.append("<option value=''>Sem períodos disponíveis</option>");
                        }
                    });
                }
            });

            // Botao de Localizar Disciplinas Ofertadas
            $('#btnLocalizar').click(function () {
                var turma = selectTurmas.val();
                var periodo = selectPeriodosLetivos.val();
                var modulo = selectModulosMatriz.val();

                if(turma == '' || periodo == '' || modulo == '') {
                    return false;
                }

                renderTables(turma, periodo, modulo);
            });

            $(document).on('click', '.btnAdicionar', function (e) {
                e.preventDefault();

                var disciplina = $(e.target).data('mdc');
                var tipo_avaliacao = $(e.target).closest('tr').find('.tipo-avaliacao').val();
                var qtd_vagas = $(e.target).closest('tr').find('.qtd-vagas').val();
                var professor = $(e.target).closest('tr').find('.professor').val();
                var turma = selectTurmas.val();
                var periodo = selectPeriodosLetivos.val();
                var modulo = selectModulosMatriz.val();

                if (turma == '' || periodo == '' || professor == '' || !(qtd_vagas > 0)
                    || tipo_avaliacao == '' || disciplina == '') {
                    return false;
                }

                var dados = {
                    ofd_trm_id: turma,
                    ofd_per_id: periodo,
                    ofd_prf_id: professor,
                    ofd_mdc_id: disciplina,
                    ofd_qtd_vagas: qtd_vagas,
                    ofd_tipo_avaliacao: tipo_avaliacao,
                    _token: token
                };

                $.harpia.showloading();

                $.ajax({
                    url: "{{url('/')}}/academico/async/ofertasdisciplinas/oferecerdisciplina",
                    data: dados,
                    method: 'POST',
                    success: function(response) {
                        $.harpia.hideloading();
                        toastr.success(response.message, null, {progressBar: true});
                        renderTables(turma, periodo, modulo);
                    },
                    error: function(response) {
                        $.harpia.hideloading();
                        var obj = response.responseJSON;
                        toastr.error(obj.error, null, {progressBar: true});
                    }
                });
            });

            var renderTableOfertasDisciplinas = function (turmaId, periodoId) {
                var url = "{{url('/')}}/academico/async/ofertasdisciplinas/gettableofertasdisciplinas?" +
                        "ofd_trm_id=" + turmaId + "&ofd_per_id=" + periodoId;

                $.harpia.showloading();
                $.ajax({
                    method: 'GET',
                    url: url,
                    success: function(response) {
                        $.harpia.hideloading();
                        $('#table-ofertas').empty();
                        $('#table-ofertas').append(response.html);
                        $('select').select2();
                    },
                    error: function(response) {
                        $.harpia.hideloading();
                        toastr.error('Erro ao processar requisição. Entrar em contato com o suporte.', null, {progressBar: true});
                    }
                });
            };

            var renderTableDisciplinasNaoOfertadas = function(turmaId, periodoId, moduloId) {
                var url = "{{url('/')}}/academico/async/ofertasdisciplinas/gettabledisciplinasnaoofertadas?" +
                    "ofd_trm_id=" + turmaId + "&ofd_per_id=" + periodoId + "&mdo_id=" + moduloId;

                $.harpia.showloading();
                $.ajax({
                    method: 'GET',
                    url: url,
                    success: function(response) {
                        $.harpia.hideloading();
                        $('#table-disciplinas').empty();
                        $('#table-disciplinas').append(response.html);
                        $('#table-disciplinas select').select2();
                    },
                    error: function(response) {
                        $.harpia.hideloading();
                        toastr.error('Erro ao processar requisição. Entrar em contato com o suporte.', null, {progressBar: true});
                    }
                });
            };

            var renderTables = function(turma, periodo, modulo) {
                renderTableOfertasDisciplinas(turma, periodo);
                renderTableDisciplinasNaoOfertadas(turma, periodo, modulo);
            };

            });
    </script>
@endsection
