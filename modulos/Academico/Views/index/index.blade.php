@extends('layouts.modulos.academico')

@section('title')
    Módulo Acadêmico
@stop

@section('subtitle')
    Módulo de gerenciamento acadêmico
@stop

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>{{ $alunos }}</h3>
                        <p>Alunos</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-users" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{ $matriculas }}</h3>
                        <p>Matrículas</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{ $cursos }}</h3>
                        <p>Cursos</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-university" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{ $turmas }}</h3>
                        <p>Turmas</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-book" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Matrículas nos últimos 6 meses</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="chart matriculasmes">
                            <canvas id="matriculasmes" width="undefined" height="undefined"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Cursos por nível</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="chart curso">
                            <canvas id="curso" width="undefined" height="undefined"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Matrículas por status</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="chart matricula">
                            <canvas id="matricula" width="undefined" height="undefined"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="{{asset('/js/plugins/Chart.min.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
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
                url: "{{ route("academico.async.dashboard.cursopornivel") }}",
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
                url: "{{ route("academico.async.dashboard.matriculasstatus") }}",
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
                url: "{{ route("academico.async.dashboard.matriculasmes") }}",
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
    </script>
@endsection
