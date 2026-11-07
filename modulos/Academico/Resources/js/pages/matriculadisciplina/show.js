import $ from 'jquery';

const baseUrl = $('meta[name="base-url"]').attr('content');
const csrfToken = $('meta[name="csrf-token"]').attr('content');

$(document).ready(function() {
    var token = csrfToken;
    var alunoId = window.PageData.alu_id;

    $('#crs_id').change(function () {
        var turmaId = $(this).find('option:selected').attr('data-trm-id');
        var selectPeriodos = $('#ofd_per_id');

        if(turmaId) {
            $.harpia.httpget(baseUrl + "/academico/async/periodosletivos/findallbyturma/"+turmaId)
                .done(function (response) {
                    selectPeriodos.empty();
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

    // Botao de Localizar Disciplinas Ofertadas
    $('#btnLocalizar').click(function () {
        var turma = $('#crs_id option:selected').attr('data-trm-id');
        var periodo = $('#ofd_per_id').val();

        if(turma == '' || periodo == '') {
            return false;
        }

        renderTable(turma, periodo, alunoId);
    });

    // evento para selecionar todos os checkboxes
    $('.tabela-ofertas').on('click', '#select_all',function(event) {
        if(this.checked) {
            $('.matricular').each(function() {
                this.checked = true;
            });
        }
        else {
            $('.matricular').each(function() {
                this.checked = false;
            });
        }
    });

    // evento para selecionar todos os checkboxes
    $('.tabela-ofertas').on('click', '#select_all_desmatricular',function(event) {
        if(this.checked) {
            $('.desmatricular').each(function() {
                this.checked = true;
            });
        }
        else {
            $('.desmatricular').each(function() {
                this.checked = false;
            });
        }
    });

    var hiddenButtonDesmatricular = function () {
        var checkboxes = $('.table-desmatricular input[type="checkbox"]');

        if(checkboxes.is(':checked')){
            $(document).find('#confirmDesmatricular').removeClass('hidden');
        }else{
            $(document).find('#confirmDesmatricular').addClass('hidden');
        }
    };

    var hiddenButton = function () {
        var checkboxes = $('.table-matricular input[type="checkbox"]');

        if(checkboxes.is(':checked')){
            $(document).find('#confirmMatricula').removeClass('hidden');
        }else{
            $(document).find('#confirmMatricula').addClass('hidden');
        }
    };

    $(document).on('click', '.table-matricular input[type="checkbox"]', hiddenButton);
    $(document).on('click', '.table-desmatricular input[type="checkbox"]', hiddenButtonDesmatricular);

    // evento do botão de confirmar a matricula na(s) disciplina(s)
    $('.tabela-ofertas').on('click', '#confirmMatricula', function (e) {

        var quant = $('.matricular:checked').length;
        if(!(quant > 0)) {
            return false;
        }
        var ofertasIds = new Array();
        var matriculaId = $('#crs_id option:selected').attr('data-mat-id');
        $('.matricular:checked').each(function () {
            ofertasIds.push($(this).val());
        });

        sendDisciplinas(matriculaId,ofertasIds);
    });

    // evento do botão de confirmar a matricula na(s) disciplina(s)
    $('.tabela-ofertas').on('click', '#confirmDesmatricular', function (e) {

        var quant = $('.desmatricular:checked').length;
        if(!(quant > 0)) {
            return false;
        }
        var ofertasIds = new Array();
        var matriculaId = $('#crs_id option:selected').attr('data-mat-id');
        $('.desmatricular:checked').each(function () {
            ofertasIds.push($(this).val());
        });

        sendDisciplinas(matriculaId,ofertasIds, true);
    });

    var renderTable = function(turmaId, periodoId, alunoId) {
        console.log(baseUrl + "/academico/async/matriculasofertasdisciplinas/gettableofertasdisciplinas/"+alunoId+"/"+turmaId+"/"+periodoId);
        $.harpia.httpget(baseUrl + "/academico/async/matriculasofertasdisciplinas/gettableofertasdisciplinas/"+alunoId+"/"+turmaId+"/"+periodoId)
            .done(function(response) {
                $('.tabela-ofertas').empty();
                $('.tabela-ofertas').append(response);
        });
    };

    var sendDisciplinas = function (matriculaId, ofertasIds, desmatricular = false) {

        if (desmatricular) {
            var url = '/academico/async/matriculasofertasdisciplinas/desmatricular';
        } else {
            var url = '/academico/async/matriculasofertasdisciplinas/matricular';
        }

        var dados = {
            ofertas: ofertasIds,
            mof_mat_id: matriculaId,
            _token: token
        };

        $.harpia.showloading();

        var result = false;

        $.ajax({
            type: 'POST',
            url: url,
            data: dados,
            success: function (data) {
                $.harpia.hideloading();

                if (desmatricular) {
                    toastr.success('Aluno desmatriculado com sucesso!', null, {progressBar: true});
                } else {
                    toastr.success('Aluno matriculado com sucesso!', null, {progressBar: true});
                }


                var turma = $('#crs_id option:selected').attr('data-trm-id');
                var periodo = $('#ofd_per_id').val();

                renderTable(turma, periodo, alunoId);
            },
            error: function (xhr, textStatus, error) {
                $.harpia.hideloading();

                switch (xhr.status) {
                    case 400:
                        toastr.error(xhr.responseText.replace(/\"/g, ''), null, {progressBar: true});
                        break;
                    default:
                        toastr.error(xhr.responseText.replace(/\"/g, ''), null, {progressBar: true});

                        result = false;
                }
            }
        });
    };

});