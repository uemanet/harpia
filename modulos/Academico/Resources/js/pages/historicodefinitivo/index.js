import $ from 'jquery';

const baseUrl = $('meta[name="base-url"]').attr('content');
const csrfToken = $('meta[name="csrf-token"]').attr('content');

$(function () {
    var token = "{{csrf_token()}}";

    var cursosSelect = $('#crs_id');
    var ofertasCursoSelect = $('#ofc_id');
    var turmaSelect = $('#trm_id');
    var polosSelect = $('#pol_id');

    // evento change do select de cursos
    cursosSelect.change(function () {
        // limpando selects
        ofertasCursoSelect.empty();
        turmaSelect.empty();
        polosSelect.empty();

        var cursoId = $(this).val();

        if(!cursoId) {
            return false;
        }

        // faz a consulta pra trazer todas as ofertas de curso
        $.harpia.httpget('{{url("/")}}/academico/async/ofertascursos/findallbycurso/' + cursoId).done(function (response) {
            if(!$.isEmptyObject(response)) {
                ofertasCursoSelect.append("<option value=''>Selecione uma oferta</option>");

                $.each(response, function (key, obj) {
                    ofertasCursoSelect.append("<option value='"+obj.ofc_id+"'>"+obj.ofc_ano+" ("+obj.mdl_nome+")</option>");
                });
            } else {
                ofertasCursoSelect.append("<option value=''>Sem ofertas cadastradas</option>");
            }
        });
    });

    // evento change do select de ofertas de curso
    ofertasCursoSelect.change(function () {
        // limpando selects
        turmaSelect.empty();
        polosSelect.empty();

        // faz a consulta pra trazer todas as turmas da oferta de curso escolhida
        var ofertaCursoId = $(this).val();

        if(!ofertaCursoId) {
            return false;
        }

        // buscar turmas
        $.harpia.httpget("{{url('/')}}/academico/async/turmas/findallbyofertacurso/" + ofertaCursoId).done(function (response) {
            if(!$.isEmptyObject(response)) {
                turmaSelect.append("<option value=''>Selecione uma turma</option>");

                $.each(response, function (key, obj) {
                    turmaSelect.append("<option value='"+obj.trm_id+"'>"+obj.trm_nome+"</option>");
                });
            } else {
                turmaSelect.append("<option value=''>Sem turmas cadastradas</option>");
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

    // evento para selecionar todos os checkboxes
    $(document).on('click', '#select_all',function(event) {
        if(this.checked) {
            $(':checkbox').each(function() {
                this.checked = true;
            });
        }
        else {
            $(':checkbox').each(function() {
                this.checked = false;
            });
        }
    });

    // evento click do botao de buscar
    $('#btnBuscar').click(function (event) {
        event.preventDefault();

        var ofertaCursoId = $('#ofc_id').val();
        var turmaId = $('#trm_id').val();
        var poloId = $('#pol_id').val();

        if(!ofertaCursoId || !turmaId || !poloId) {
            return false;
        }

        renderTableAlunos(ofertaCursoId, turmaId, poloId);
    });

    var renderTableAlunos = function (ofertaCursoId, turmaId, poloId) {

        var data = 'trm_ofc_id=' + ofertaCursoId + '&mat_trm_id=' + turmaId + '&mat_pol_id=' + poloId;

        $.harpia.httpget("{{url('/')}}/academico/async/matricula/getmatriculasconcluidas?" + data).done(function (response) {

            $('#cardAlunos').removeClass('hidden');

            var cardAlunos = $('#cardAlunos .card-body');
            cardAlunos.empty();



            if(!$.isEmptyObject(response)) {
                var historicodefinitivo_print = window.PageRoutes.historicodefinitivo_print;
                var table = '';
                table = '<div class="row">';
                table += '<form action="'+ historicodefinitivo_print +'" method="POST">';
                table += '<div class="col-md-12">';
                table += '{{csrf_field()}}';
                table += '<table class="table table-bordered table-hover">';

                table += '<tr>';
                table += '<th width="1%"><label><input id="select_all" type="checkbox"></label></th>';
                table += '<th width="1%">#</th>';
                table += '<th>Aluno</th>';
                table += '<th width="20%">Situação</th>';
                table += '<th width="20%">Data de Conclusão</th>';
                table += '</tr>';

                $.each(response, function (key, obj) {
                    table += '<tr>';
                    table += '<td><label><input type="checkbox" name="matriculas[]" class="matriculas" value="'+obj.mat_id+'"></label></td>';
                    table += '<td>'+obj.mat_id+'</td>';
                    table += '<td>'+obj.pes_nome+'</td>';
                    table += '<td><span class="label label-success">Concluído</span></td>';
                    table += '<td>'+obj.mat_data_conclusao+'</td>';
                    table += '</tr>';
                });

                table += '</table>';

                table += "<div class='form-group'>";
                table += "<button type='submit' class='btn btn-primary impHistoricos hidden' formtarget='_blank'><i class='fa fa-file-pdf-o'></i> Imprimir Históricos</button>";
                table += "</div>";
                table += "</div>";
                table += "</form>";
                table += "</div>";

                cardAlunos.append(table);
                hiddenButton();
            } else {
                cardAlunos.append('<p>Sem registros para apresentar</p>');
            }
        });
    };

    var hiddenButton = function() {
        var checkboxes = $('#cardAlunos table td input[type="checkbox"]');

        if(checkboxes.is(':checked')){
            $(document).find('.impHistoricos').removeClass('hidden');
        }else{
            $(document).find('.impHistoricos').addClass('hidden');
        }
    };

    $(document).on('click', '#cardAlunos table input[type="checkbox"]', hiddenButton);
});