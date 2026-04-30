import $ from 'jquery';
const baseUrl = $('meta[name="base-url"]').attr('content');

$(document).ready(function() {
    var token = window.PageData.csrf_token;
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

    $(document).on("click", ".modalButton",function(event){

        event.preventDefault();

        var ofertaCursoId = $(this).attr('data-ofc-id');

        var matriculaId = $(document).find('option:selected').attr('data-mat-id');

        $.harpia.httpget(baseUrl + "/academico/async/aproveitamentoestudos/getmodal/"+ofertaCursoId + "/" + matriculaId)
            .done(function(response) {
                $('.modal').empty();
                $('.modal').append(response);
            });

        $('#matricula-modal').modal();
    });

    var renderModal = function(ofertaId) {

        $.harpia.httpget(baseUrl + "/academico/async/aproveitamentoestudos/getmodal/"+ofertaId)
            .done(function(response) {
                $('.modal').empty();
                $('.modal').append(response);
            });
    };

    // Botao de Localizar Disciplinas Ofertadas
    $('#btnLocalizar').on('click',function(){
        var turma = $('#crs_id option:selected').attr('data-trm-id');
        var periodo = $('#ofd_per_id').val();

        if(!turma ) {
            return false;
        }

        if (periodo == '') {
            periodo = null;
        }

        renderTable(turma, periodo, alunoId);
    });

    var renderTable = function(turmaId, periodoId, alunoId) {

        if (periodoId) {
            $.harpia.httpget(baseUrl + "/academico/async/aproveitamentoestudos/gettableofertasdisciplinas/"+alunoId+"/"+turmaId+"/"+periodoId)
                .done(function(response) {
                    $('#tabela-ofertas').empty();
                    $('#tabela-ofertas').append(response);
                });
        }else {
            $.harpia.httpget(baseUrl + "/academico/async/aproveitamentoestudos/gettableofertasdisciplinas/"+alunoId+"/"+turmaId)
                .done(function(response) {
                    $('#tabela-ofertas').empty();
                    $('#tabela-ofertas').append(response);
                });
        }
    };
});