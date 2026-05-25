import $ from 'jquery';
import Chart from "chart.js/auto";

const baseUrl = $('meta[name="base-url"]').attr('content');
const csrfToken = $('meta[name="csrf-token"]').attr('content');

$(document).ready(function () {
    $('#crs_id').prop('selectedIndex', 0);

    console.log('teste')
});

$('#crs_id').change(function (e) {
    var crsId = $(this).val();

    var selectOfertas = $('#ofc_id');
    var selectTurmas = $('#trm_id');
    if (crsId) {

        // Populando o select de ofertas de cursos
        selectOfertas.empty();
        selectTurmas.empty();

        $.harpia.httpget(baseUrl + "/academico/async/ofertascursos/findallbycurso/" + crsId)
            .done(function (data) {
                if (!$.isEmptyObject(data)) {
                    selectOfertas.append("<option>Selecione a oferta</option>");
                    $.each(data, function (key, value) {
                        selectOfertas.append('<option value="' + value.ofc_id + '">' + value.ofc_ano + '</option>');
                    });
                } else {
                    selectOfertas.append("<option>Sem ofertas cadastradas</option>");

                }
            });
    }
});

$('#ofc_id').change(function (e) {
    var ofertaId = $(this).val();

    var selectTurmas = $('#trm_id');

    if (ofertaId) {
        selectTurmas.empty();

        $.harpia.httpget(baseUrl + '/academico/async/turmas/findallbyofertacursointegrada/' + ofertaId)
            .done(function (data) {
                if (!$.isEmptyObject(data)) {
                    selectTurmas.append('<option>Selecione a turma</option>');
                    $.each(data, function (key, obj) {
                        selectTurmas.append('<option value="' + obj.trm_id + '">' + obj.trm_nome + '</option>')
                    });
                } else {
                    selectTurmas.append('<option>Sem turmas cadastradas</option>')
                }
            });
    }

})

$('#trm_id').change(function (e) {
    var turmaId = $(this).val();
    var selectGrupos = $('#grp_id');

    if (turmaId) {
        selectGrupos.empty();
        selectGrupos.append('<option>Selecione o grupo</option>');
        selectGrupos.append('<option value="presencial">Presencial</option>')
        selectGrupos.append('<option value="distancia">Distância</option>')

    }

})

$('#grp_id').change(function (e) {
    var turmaId = $('#trm_id').val();
    var tipotutoria = $(this).val();
    var selectTutores = $('#tut_id');

    if (turmaId) {

        selectTutores.empty();
        $.harpia.httpget(baseUrl + '/academico/async/tutores/findallbyturmatipotutoria/' + turmaId + '/' + tipotutoria)
            .done(function (data) {
                if (!$.isEmptyObject(data)) {
                    selectTutores.append('<option>Selecione o tutor</option>');
                    $.each(data, function (key, obj) {
                        selectTutores.append('<option value="' + obj.pes_id + '">' + obj.pes_nome + '</option>')
                    });
                } else {
                    selectTutores.append('<option>Sem tutores cadastrados nessa turma</option>')
                }
            });
    }

})

$(document).on('click', '.btn-primary', function (event) {
    event.preventDefault();

    var tutor = $('#tut_id').val();

    // 1. Validação de segurança: Interrompe se nenhum tutor foi selecionado
    if (!tutor || tutor.length === 0) {
        toastr.warning('Por favor, selecione pelo menos um tutor.', null, {progressBar: true});
        return;
    }

    // 2. Garante que tutor seja tratado como array (útil se o select não for múltiplo)
    if (!Array.isArray(tutor)) {
        tutor = [tutor];
    }

    var array = new Array();
    for (var i = 0; i < tutor.length; i++) {
        array.push(parseInt(tutor[i]));
    }

    var datainicio = $('#date_ini').val().replace(/\//g, "\-");
    var datafim = $('#date_fim').val().replace(/\//g, "\-");
    var token = window.PageData.asr_token;
    var timeclicks = window.PageData.timeclicks;
    var moodlewsformat = "json";
    var wsfunction = window.PageData.wsfunction;
    var url = window.PageData.amb_url;

    // 3. Criação do Canvas FORA do loop.
    // Isso garante que ele seja recriado apenas uma vez ao clicar no botão.
    var grafico = $('#grafico');
    if (grafico.length === 0) {
        console.error('ERRO: A div <div id="grafico"></div> não foi encontrada no HTML da página.');
        return;
    }

    grafico.empty();
    grafico.append('<canvas id="grafico-tempo" height="400"></canvas>');

    var dadosgrafico = new Array();

    // Loop de requisições
    for (var i = 0; i < tutor.length; i++) {
        $.ajax({
            url: url + "webservice/rest/server.php?wstoken=" + token + "&wsfunction=" + wsfunction + "&startdate=" + datainicio + "&enddate=" + datafim + "&pesid=" + array[i] + "&timebetweenclicks=" + timeclicks + "&moodlewsrestformat=" + moodlewsformat,
            type: "POST",
            dataType: "json",
            async: false, // Nota: async false congela a tela do usuário. Se possível, refatore para Promises no futuro.
            success: function (moodledata) {
                $.harpia.hideloading();

                if (moodledata.errorcode === "startdateerror") {
                    toastr.error('A data de fim não deve ser menor que a data de início', null, {progressBar: true});
                } else if (moodledata.errorcode === "enddateerror") {
                    toastr.error('A data de fim não deve maior que o dia atual', null, {progressBar: true});
                } else {
                    dadosgrafico.push(moodledata);
                }
            },
            error: function (error) {
                $.harpia.hideloading();
                toastr.error('Erro ao tentar se comunicar com o Ambiente Virtual.', null, {progressBar: true});
            }
        });
    }

    // Se a requisição falhou e não trouxe dados, não tentamos gerar o gráfico
    if (dadosgrafico.length === 0) {
        return;
    }

    var dadosDatasets = new Array();
    var dias = new Array();
    var tempos = new Array();

    for (var i = 0; i < dadosgrafico.length; i++) {
        var parada = dadosgrafico[i].items;

        // Validação extra: caso a API retorne algo sem a propriedade items
        if (!parada) continue;

        for (var j = 0; j < parada.length; j++) {
            dias[j] = parada[j].date.replace(/-/g, "\/");
            tempos[j] = parada[j].onlinetime;
        }

        var dataset = {
            label: dadosgrafico[i].fullname,
            data: tempos,
            fill: true,
            backgroundColor: 'rgba(255, ' + Math.floor((Math.random() * 255) + 1) + ', ' + Math.floor((Math.random() * 255) + 1) + ', .6)'
        }
        tempos = [];
        dadosDatasets.push(dataset);
    }

    // var config = {
    //     type: 'line',
    //     data: {
    //         labels: dias,
    //         datasets: dadosDatasets
    //     },
    //     options: {
    //         responsive: true,
    //         maintainAspectRatio: false,
    //         // (Mantenha o restante das suas options aqui...)
    //         plugins: {
    //             title: {
    //                 display: true,
    //                 text: 'Gráfico de Acesso ao AVA'
    //             }
    //         }
    //     }
    // };
    var config = {
        type: 'line',
        data: {
            labels: dias,
            datasets: dadosDatasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Gráfico de Acesso ao AVA'
                },
                tooltip: {
                    mode: 'index', // 'label' foi substituído por 'index' na v3+
                    intersect: false,
                    bodyColor: "#fff",
                    bodyFont: {
                        weight: "bold",
                        family: "'Helvetica', 'Arial', sans-serif"
                    },
                    footerFont: {
                        size: 15
                    }
                }
            },
            hover: {
                mode: 'nearest',
                intersect: true
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: false,
                        text: 'Dias'
                    }
                },
                y: {
                    display: true,
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Tempo Online (Segundos)'
                    },
                    stacked: true
                }
            }
        }
    };

    // 4. Verificação final antes de aplicar o Chart.js
    var canvasElement = document.getElementById('grafico-tempo');
    if (canvasElement) {
        var monitoramento = canvasElement.getContext('2d');
        new Chart(monitoramento, config);
    } else {
        console.error('O canvas #grafico-tempo ainda não existe na DOM.');
    }
});

// $(document).on('click', '.btn-primary', function (event) {
//     event.preventDefault();
//
//     var array = new Array();
//
//     var tutor = $('#tut_id').val();
//
//     for (var i = 0; i < tutor.length; i++) {
//         array.push(parseInt(tutor[i]));
//     }
//
//     var datainicio = $('#date_ini').val().replace(/\//g, "\-");
//     var datafim = $('#date_fim').val().replace(/\//g, "\-");
//     var token = window.PageData.asr_token;
//     var timeclicks = window.PageData.timeclicks;
//     var moodlewsformat = "json";
//     var wsfunction = window.PageData.wsfunction;
//     var url = window.PageData.amb_url;
//
//     var dadosgrafico = new Array();
//
//     for (var i = 0; i < tutor.length; i++) {
//         if (i === 0) {
//
//             var grafico = $('#grafico');
//             grafico.empty();
//             grafico.append('<canvas id="grafico-tempo" height="400"></canvas>');
//         }
//
//         var request = $.ajax({
//             url: url + "webservice/rest/server.php?wstoken=" + token + "&wsfunction=" + wsfunction + "&startdate=" + datainicio + "&enddate=" + datafim + "&pesid=" + array[i] + "&timebetweenclicks=" + timeclicks + "&moodlewsrestformat=" + moodlewsformat,
//             type: "POST",
//             dataType: "json",
//             async: false,
//             success: function (moodledata) {
//                 $.harpia.hideloading();
//
//                 if (moodledata.errorcode === "startdateerror") {
//                     toastr.error('A data de fim não deve ser menor que a data de início', null, {progressBar: true});
//                 }
//
//                 if (moodledata.errorcode === "enddateerror") {
//                     toastr.error('A data de fim não deve maior que o dia atual', null, {progressBar: true});
//                 }
//                 dadosgrafico.push(moodledata);
//             },
//             error: function (error) {
//                 $.harpia.hideloading();
//                 toastr.error('Erro ao tentar se comunicar com o Ambiente Virtual.', null, {progressBar: true});
//             }
//         });
//     }
//
//     var dadosDatasets = new Array();
//
//     var dias = new Array();
//     var tempos = new Array();
//     var parada = new Array();
//
//     for (var i = 0; i < dadosgrafico.length; i++) {
//
//         parada = dadosgrafico[i].items;
//
//         for (var j = 0; j < parada.length; j++) {
//             dias[j] = parada[j].date.replace(/-/g, "\/");
//             tempos[j] = parada[j].onlinetime;
//         }
//
//         var dataset = {
//             label: dadosgrafico[i].fullname,
//             data: tempos,
//             fill: true,
//             backgroundColor: 'rgba(255, ' + Math.floor((Math.random() * 255) + 1) + ', ' + Math.floor((Math.random() * 255) + 1) + ', .6)'
//         }
//         tempos = [];
//
//         dadosDatasets.push(dataset);
//     }
//
//     var config;
//     config = {
//         type: 'line',
//         data: {
//             labels: dias,
//             datasets: dadosDatasets
//         },
//         options: {
//             responsive: true,
//             maintainAspectRatio: false,
//             title: {
//                 display: true,
//                 text: 'Gráfico de Acesso ao AVA'
//             },
//             hover: {
//                 mode: 'nearest',
//                 intersect: true
//             },
//             scales: {
//                 xAxes: [{
//                     display: true,
//                     beginAtZero: true,
//                     scaleLabel: {
//                         display: false,
//                         labelString: 'Dias'
//                     }
//                 }],
//                 yAxes: [{
//                     display: true,
//                     beginAtZero: true,
//                     // ticks: {
//                     //   suggestedMax: 6,
//                     //   fixedStepSize: 1
//                     // },
//                     scaleLabel: {
//                         display: true,
//                         labelString: 'Tempo Online (Segundos)',
//                         stacked: true
//                     }
//                 }]
//             },
//             tooltips: {
//
//                 mode: 'label',
//
//                 intersect: false,
//                 bodyFontColor: "#fff",
//                 bodyFontStyle: "bold",
//                 bodyFontFamily: "'Helvetica', 'Arial', sans-serif",
//                 footerFontSize: 15,
//                 // callbacks: {
//                 //     label: function (tooltipItem, data) {
//                 //
//                 //         for(var i = 0; i < data.datasets.lenght; i++){
//                 //             var moodle = dadosgrafico[i];
//                 //             var seconds = moodle.items[tooltipItem.index].onlinetime;
//                 //
//                 //             var h = Math.floor(seconds / 3600);
//                 //             var m = Math.floor(seconds % 3600 / 60);
//                 //             var s = Math.floor(seconds % 3600 % 60);
//                 //             var humanFormat = h + 'h:' + m + 'm:' + s + 's';
//                 //
//                 //             return humanFormat;
//                 //         }
//                 //     },
//                 // },
//             }
//         }
//     };
//
//     // get line chart canvas
//     var monitoramento = document.getElementById('grafico-tempo').getContext('2d');
//     // draw line chart
//
//     new Chart(monitoramento, config);
// });