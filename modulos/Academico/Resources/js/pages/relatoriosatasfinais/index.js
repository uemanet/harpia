import $ from 'jquery';

const baseUrl = $('meta[name="base-url"]').attr('content');
const csrfToken = $('meta[name="csrf-token"]').attr('content');

$(function () {

    var ofertasCursoSelect = $('#ofc_id');
    var turmaSelect = $('#trm_id');
    var polosSelect = $('#pol_id');
    var routePdf = "{{{ route('academico.relatoriosatasfinais.pdf') }}}";
    var routeIndex = "{{{ route('academico.relatoriosatasfinais.index') }}}";
    var cursoSelect = $('#crs_id');
    var situacaoSelect = $('#mat_situacao');

    // evento change select de cursos
    $('#crs_id').change(function () {

        // limpando selects
        ofertasCursoSelect.empty();
        turmaSelect.empty();
        polosSelect.empty();

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



    $(document).on('click', '#btnBuscar', function () {
        $('#form').attr('action', routeIndex).removeAttr('target', '_blank').submit();
    });

    $('#turmaId').attr('value', turmaSelect.val());
    $('#ofertaCursoId').attr('value', ofertasCursoSelect.val());
    $('#poloId').attr('value', polosSelect.val());
    $('#cursoId').attr('value', cursoSelect.val());
    $('#situacao').attr('value', situacaoSelect.val());

    $('#pturmaId').attr('value', turmaSelect.val());
    $('#pofertaCursoId').attr('value', ofertasCursoSelect.val());
    $('#ppoloId').attr('value', polosSelect.val());
    $('#pcursoId').attr('value', cursoSelect.val());
    $('#psituacao').attr('value', situacaoSelect.val());

    $(document).on('click', '.pdfButton', function (event) {
        $.harpia.showloading();
        $('#exportPdf').submit();
    });
});