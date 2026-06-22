// Importa dependências se necessário
import $ from 'jquery';
import Chart from 'chart.js/auto';

const baseUrl = $('meta[name="base-url"]').attr('content');
const csrfToken = $('meta[name="csrf-token"]').attr('content');

$(document).ready(function (e) {
    window.chartColors = {
        red: 'rgb(255, 99, 132)',
        orange: 'rgb(255, 159, 64)',
        yellow: 'rgb(255, 205, 86)',
        green: 'rgb(75, 192, 192)',
        blue: 'rgb(54, 162, 235)',
        purple: 'rgb(153, 102, 255)',
        grey: 'rgb(201, 203, 207)'
    };

    // Cursos por nivel
    $.ajax({
        url: window.PageRoutes.cursopornivel,
        type: "GET",
        success: function (data) {
            var dataSet = [];
            var dataLabels = [];

            for(var i = 0; i < data.length; i++){
                dataSet.push(data[i].quantidade);
                dataLabels.push(data[i].nvc_nome);
            }

            var config = {
                type: "pie",
                data: {
                    datasets: [{
                        data: dataSet,
                        backgroundColor: [
                            window.chartColors.red,
                            window.chartColors.blue,
                            window.chartColors.yellow,
                            window.chartColors.green,
                            window.chartColors.orange
                        ]
                    }],
                    labels: dataLabels
                },
                options: {
                    radiusBackground: {
                        color: '#d1d1d1'
                    }
                }
            };

            var area = document.getElementById("curso").getContext('2d');

            new Chart(area, config);
        },
        error: function (error) {
            $(".curso").empty();
            $(".curso").append("<p>Sem dados disponíveis</p>");
        }
    });

    // Matriculas
    $.ajax({
        url: window.PageRoutes.matriculasstatus,
        type: "GET",
        success: function (data) {
            var dataSet = [];
            var dataLabels = [];

            for(var i = 0; i < data.length; i++){
                dataSet.push(data[i].quantidade);
                dataLabels.push(data[i].mat_situacao);
            }

            var config = {
                type: "pie",
                data: {
                    datasets: [{
                        data: dataSet,
                        backgroundColor: [
                            window.chartColors.red,
                            window.chartColors.blue,
                            window.chartColors.yellow,
                            window.chartColors.green,
                            window.chartColors.orange
                        ]
                    }],
                    labels: dataLabels
                },
                options: {
                    radiusBackground: {
                        color: '#d1d1d1'
                    }
                }
            };

            var area = document.getElementById("matricula").getContext('2d');

            new Chart(area, config);
        },
        error: function (error) {
            $(".matricula").empty();
            $(".matricula").append("<p>Sem dados disponíveis</p>");
        }
    });

    // Matriculas mes
    $.ajax({
        url: window.PageRoutes.matriculasmes,
        type: "GET",
        success: function (data) {
            var dataSet = [];
            var dataLabels = [];

            for(var i = 0; i < data.length; i++){
                dataSet.push(data[i].quantidade);
                dataLabels.push(data[i].mes);
            }

            var config = {
                type: "line",
                data: {
                    datasets: [{
                        label: "Matrículas",
                        data: dataSet,
                        backgroundColor: window.chartColors.red,
                        fill: true
                    }],
                    labels: dataLabels
                },
                options: {
                    radiusBackground: {
                        color: '#d1d1d1'
                    }
                }
            };

            var area = document.getElementById("matriculasmes").getContext('2d');

            new Chart(area, config);
        },
        error: function (error) {
            $(".matriculasmes").empty();
            $(".matriculasmes").append("<p>Sem dados disponíveis</p>");
        }
    });
});