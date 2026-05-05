import $ from 'jquery';

const baseUrl = $('meta[name="base-url"]').attr('content');
const csrfToken = $('meta[name="csrf-token"]').attr('content');

$(function () {
    var ofertasCursoSelect = $('#ofc_id');
    var cursoSelect = $('#crs_id');
    var turmaSelect = $('#trm_id');
    var polosSelect = $('#pol_id');
    var periodosLetivosSelect = $('#per_id');
    var disciplinasOfertadasSelect = $('#ofd_id');
    var btnBuscar = $('#btnBuscar');
    var routeIndex = "{{{ route('academico.relatoriosmatriculasdisciplinas.index') }}}";
    var situacaoSelect = $('#mof_situacao_matricula');

    // evento change select de cursos
    $('#crs_id').change(function () {

        // limpando selects
        ofertasCursoSelect.empty();
        turmaSelect.empty();
        polosSelect.empty();
        periodosLetivosSelect.empty();
        disciplinasOfertadasSelect.empty();

        // buscar as ofertas de curso de acordo com o curso escolhido
        var cursoId = $(this).val();

        if (!cursoId || cursoId == '') {
            return false;
        }

        $.harpia.httpget("{{url('/')}}/academico/async/ofertascursos/findallbycurso/" + cursoId).done(function (response) {
            if (!$.isEmptyObject(response)) {
                ofertasCursoSelect.append("<option value=''>Selecione a oferta</option>");
                $.each(response, function (key, obj) {
                    ofertasCursoSelect.append('<option value="' + obj.ofc_id + '">' + obj.ofc_ano + ' (' + obj.mdl_nome + ')</option>');
                });
            } else {
                ofertasCursoSelect.append("<option>Sem ofertas disponiveis</option>");
            }
        });
    });

    // evento change select de ofertas de curso
    ofertasCursoSelect.change(function () {

        //limpando selects
        turmaSelect.empty();
        periodosLetivosSelect.empty();
        disciplinasOfertadasSelect.empty();
        polosSelect.empty();

        // buscar as turmas de acordo com a oferta de curso
        var ofertaCursoId = $(this).val();

        if (!ofertaCursoId || ofertaCursoId == '') {
            return false;
        }

        $.harpia.httpget("{{url('/')}}/academico/async/turmas/findallbyofertacurso/" + ofertaCursoId).done(function (response) {
            if (!$.isEmptyObject(response)) {
                turmaSelect.append('<option value="">Selecione a turma</option>');
                $.each(response, function (key, obj) {
                    turmaSelect.append('<option value="' + obj.trm_id + '">' + obj.trm_nome + '</option>');
                });
            } else {
                turmaSelect.append('<option>Sem turmas disponíveis</option>');
            }
        });

        // buscar polos
        $.harpia.httpget("{{url('/')}}/academico/async/polos/findallbyofertacurso/" + ofertaCursoId).done(function (response) {
            if(!$.isEmptyObject(response)) {
                polosSelect.append("<option value=''>Selecione um polo</option>");

                $.each(response, function (key, obj) {
                    polosSelect.append("<option value='"+obj.pol_id+"'>"+obj.pol_nome+"</option>");
                });
            } else {
                polosSelect.append("<option value=''>Sem polos cadastrados</option>");
            }
        });
    });

    // evento change select de turmas
    turmaSelect.change(function () {

        // limpando selects
        periodosLetivosSelect.empty();
        disciplinasOfertadasSelect.empty();

        // buscar os periodos letivos de acordo com a turma escolhida
        var turmaId = $(this).val();

        if (!turmaId || turmaId == '') {
            return false;
        }

        $.harpia.httpget("{{url('/')}}/academico/async/periodosletivos/findallbyturma/" + turmaId).done(function (response) {
            if (!$.isEmptyObject(response)) {
                periodosLetivosSelect.append('<option value="">Selecione o periodo letivo</option>');
                $.each(response, function (key, obj) {
                    periodosLetivosSelect.append('<option value="' + obj.per_id + '">' + obj.per_nome + '</option>');
                });
            } else {
                periodosLetivosSelect.append('<option>Sem periodos letivos disponiveis</option>');
            }
        });
    });

    //evento change select de periodos letivos
    periodosLetivosSelect.change(function () {

        // limpando select
        disciplinasOfertadasSelect.empty();

        // buscar todas as disciplinas ofertadas de acordo com o periodo e a turma
        var turmaId = turmaSelect.val();
        var periodoLetivoId = $(this).val();

        if ((!turmaId || turmaId == '') || (!periodoLetivoId || periodoLetivoId == '')) {
            return false;
        }

        $.harpia.httpget("{{url('/')}}/academico/async/ofertasdisciplinas/findall?ofd_trm_id=" + turmaId + "&ofd_per_id=" + periodoLetivoId).done(function (response) {
            if (!$.isEmptyObject(response)) {
                disciplinasOfertadasSelect.append('<option value="">Selecione a disciplina ofertada</option>');
                $.each(response, function (key, obj) {
                    disciplinasOfertadasSelect.append('<option value="' + obj.ofd_id + '">' + obj.dis_nome + '</option>');
                });
            } else {
                disciplinasOfertadasSelect.append('<option>Sem disciplinas ofertadas disponíveis</option>');
            }
        })
    });

    $(document).on('click', '#btnBuscar', function () {
        $('#form').attr('action', routeIndex).removeAttr('target', '_blank').submit();
    });

    $('#turmaId').attr('value', turmaSelect.val());
    $('#ofertaCursoId').attr('value', ofertasCursoSelect.val());
    $('#poloId').attr('value', polosSelect.val());
    $('#cursoId').attr('value', cursoSelect.val());
    $('#periodoId').attr('value', periodosLetivosSelect.val());
    $('#ofertaDisciplinaId').attr('value', disciplinasOfertadasSelect.val());
    $('#situacao').attr('value', situacaoSelect.val());

    $('#pturmaId').attr('value', turmaSelect.val());
    $('#pofertaCursoId').attr('value', ofertasCursoSelect.val());
    $('#ppoloId').attr('value', polosSelect.val());
    $('#pcursoId').attr('value', cursoSelect.val());
    $('#pperiodoId').attr('value', periodosLetivosSelect.val());
    $('#pofertaDisciplinaId').attr('value', disciplinasOfertadasSelect.val());
    $('#psituacao').attr('value', situacaoSelect.val());

    $(document).on('click', '#formPdf', function (event) {
        $('#exportPdf').submit();
    });

});