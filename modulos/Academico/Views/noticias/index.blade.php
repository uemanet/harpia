@extends('layouts.modulos.academico')

@section('title')
    Notícias
@stop

@section('subtitle')
    Módulo Acadêmico
@stop

@section('actionButton')
    {!!ActionButton::render($actionButton)!!}
@stop

@section('content')
    {{--    <section class="content">--}}
    {{--        <div class="row">--}}
    {{--            <div class="col-lg-3 col-xs-6">--}}
    {{--                <div class="small-box bg-aqua">--}}
    {{--                    <div class="inner">--}}
    {{--                        <h3>{{ $alunos }}</h3>--}}
    {{--                        <p>Alunos</p>--}}
    {{--                    </div>--}}
    {{--                    <div class="icon">--}}
    {{--                        <i class="fa fa-users" aria-hidden="true"></i>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--            <div class="col-lg-3 col-xs-6">--}}
    {{--                <div class="small-box bg-green">--}}
    {{--                    <div class="inner">--}}
    {{--                        <h3>{{ $matriculas }}</h3>--}}
    {{--                        <p>Matrículas</p>--}}
    {{--                    </div>--}}
    {{--                    <div class="icon">--}}
    {{--                        <i class="fa fa-graduation-cap" aria-hidden="true"></i>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--            <div class="col-lg-3 col-xs-6">--}}
    {{--                <div class="small-box bg-yellow">--}}
    {{--                    <div class="inner">--}}
    {{--                        <h3>{{ $cursos }}</h3>--}}
    {{--                        <p>Cursos</p>--}}
    {{--                    </div>--}}
    {{--                    <div class="icon">--}}
    {{--                        <i class="fa fa-university" aria-hidden="true"></i>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--            <div class="col-lg-3 col-xs-6">--}}
    {{--                <div class="small-box bg-red">--}}
    {{--                    <div class="inner">--}}
    {{--                        <h3>{{ $turmas }}</h3>--}}
    {{--                        <p>Turmas</p>--}}
    {{--                    </div>--}}
    {{--                    <div class="icon">--}}
    {{--                        <i class="fa fa-book" aria-hidden="true"></i>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <div class="row">--}}
    {{--            <div class="col-md-6">--}}
    {{--                <div class="box box-primary">--}}
    {{--                    <div class="box-header with-border">--}}
    {{--                        <h3 class="box-title">Matrículas nos últimos 6 meses</h3>--}}
    {{--                        <div class="box-tools pull-right">--}}
    {{--                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>--}}
    {{--                            </button>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="box-body">--}}
    {{--                        <div class="chart matriculasmes">--}}
    {{--                            <canvas id="matriculasmes" width="undefined" height="undefined"></canvas>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--            <div class="col-md-6">--}}
    {{--                <div class="box box-primary">--}}
    {{--                    <div class="box-header with-border">--}}
    {{--                        <h3 class="box-title">Cursos por nível</h3>--}}
    {{--                        <div class="box-tools pull-right">--}}
    {{--                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>--}}
    {{--                            </button>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="box-body">--}}
    {{--                        <div class="chart curso">--}}
    {{--                            <canvas id="curso" width="undefined" height="undefined"></canvas>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <div class="row">--}}
    {{--            <div class="col-md-12 text-center">--}}
    {{--                <div class="box box-primary">--}}
    {{--                    <div class="box-header with-border">--}}
    {{--                        <h3 class="box-title">Matrículas por status</h3>--}}
    {{--                        <div class="box-tools pull-right">--}}
    {{--                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>--}}
    {{--                            </button>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                    <div class="box-body">--}}
    {{--                        <div class="chart matricula">--}}
    {{--                            <canvas id="matricula" width="undefined" height="undefined"></canvas>--}}
    {{--                        </div>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </section>--}}

    <section class="elementor-section elementor-top-section elementor-element elementor-element-f8043db elementor-section-boxed elementor-section-height-default elementor-section-height-default parallax_section_no qode_elementor_container_no" data-id="f8043db" data-element_type="section">
        <div class="elementor-container elementor-column-gap-default">
            <div class="elementor-row">
                <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-3934e81" data-id="3934e81" data-element_type="column">
                    <div class="elementor-column-wrap elementor-element-populated">
                        <div class="elementor-widget-wrap">
                            <div class="elementor-element elementor-element-93c5691 elementor-grid-1 elementor-posts--thumbnail-left elementor-posts--align-left elementor-grid-tablet-2 elementor-grid-mobile-1 elementor-widget elementor-widget-posts" data-id="93c5691" data-element_type="widget" data-settings="{&quot;classic_columns&quot;:&quot;1&quot;,&quot;classic_row_gap&quot;:{&quot;unit&quot;:&quot;px&quot;,&quot;size&quot;:30,&quot;sizes&quot;:[]},&quot;classic_columns_tablet&quot;:&quot;2&quot;,&quot;classic_columns_mobile&quot;:&quot;1&quot;}" data-widget_type="posts.classic">
                                <div class="elementor-widget-container">
                                    <div class="elementor-posts-container elementor-posts elementor-posts--skin-classic elementor-grid elementor-has-item-ratio">
{{--                                        <article class="elementor-post elementor-grid-item post-8155 post type-post status-publish format-standard hentry category-geral">--}}
{{--                                            <div class="elementor-post__text">--}}
{{--                                                <h3 class="elementor-post__title">--}}
{{--                                                    <a href="https://uemanet.uema.br/blog/2023/01/13/edital-no-38-2022-convocacao-para-entrevista-processo-de-selecao-simplificado-para-preenchimento-de-vagas-e-formacao-de-cadastro-de-reserva-de-professores-supervisores-de-estagio-p/">--}}
{{--                                                        EDITAL Nº 38/2022 – CONVOCAÇÃO PARA ENTREVISTA – PROCESSO DE SELEÇÃO SIMPLIFICADO PARA PREENCHIMENTO DE VAGAS E FORMAÇÃO DE CADASTRO DE RESERVA DE PROFESSORES SUPERVISORES DE ESTÁGIO PARA ATUAÇÃO NOS CURSOS DE LICENCIATURA EM MÚSICA E PEDAGOGIA, NA MODALIDADE A DISTÂNCIA, DA UNIVERSIDADE ESTADUAL DO MARANHÃO – UEMA.			</a>--}}
{{--                                                </h3>--}}
{{--                                                <div class="elementor-post__meta-data">--}}
{{--					<span class="elementor-post-date">--}}
{{--			13 de janeiro de 2023		</span>--}}
{{--                                                </div>--}}
{{--                                                <div class="elementor-post__excerpt">--}}
{{--                                                    <p>A Universidade Estadual do Maranhão (UEMA), por meio do Núcleo de Tecnologias para Educação (UEMAnet), torna público, para conhecimento dos interessados, a convocação para entrevista relativo ao processo para preenchimento de vagas e formação de cadastro de reserva para&nbsp;para Professor Supervisor de Estágio dos cursos de&nbsp;Licenciatura em Música e Pedagogia, na modalidade a distância, observadas as condições contidas no edital nº 38/2022.</p>--}}
{{--                                                </div>--}}
{{--                                                <a class="elementor-post__read-more" href="https://uemanet.uema.br/blog/2023/01/13/edital-no-38-2022-convocacao-para-entrevista-processo-de-selecao-simplificado-para-preenchimento-de-vagas-e-formacao-de-cadastro-de-reserva-de-professores-supervisores-de-estagio-p/">--}}
{{--                                                    Ver mais »			</a>--}}
{{--                                            </div>--}}
{{--                                        </article>--}}

                                        @foreach($noticias as $noticia)
                                            <article class="elementor-post elementor-grid-item post-8155 post type-post status-publish format-standard hentry category-geral">
                                                <div class="elementor-post__text">
                                                    <h3 class="elementor-post__title">
{{--                                                        <a href="https://uemanet.uema.br/blog/2023/01/13/edital-no-38-2022-convocacao-para-entrevista-processo-de-selecao-simplificado-para-preenchimento-de-vagas-e-formacao-de-cadastro-de-reserva-de-professores-supervisores-de-estagio-p/">--}}
{{--                                                            EDITAL Nº 38/2022 – CONVOCAÇÃO PARA ENTREVISTA – PROCESSO DE SELEÇÃO SIMPLIFICADO PARA PREENCHIMENTO DE VAGAS E FORMAÇÃO DE CADASTRO DE RESERVA DE PROFESSORES SUPERVISORES DE ESTÁGIO PARA ATUAÇÃO NOS CURSOS DE LICENCIATURA EM MÚSICA E PEDAGOGIA, NA MODALIDADE A DISTÂNCIA, DA UNIVERSIDADE ESTADUAL DO MARANHÃO – UEMA.			</a>--}}
                                                        {{$noticia->not_titulo}}
                                                    </h3>
                                                    <div class="elementor-post__meta-data">
					                                    <span class="elementor-post-date"> {{$noticia->created_at->format('d/m/Y')}}	</span>
                                                    </div>
                                                    <div class="elementor-post__excerpt">
                                                        <p>{{$noticia->not_descricao}}</p>
                                                    </div>
                                                    <a class="elementor-post__read-more" href="{{$noticia->not_link}}">
                                                        Ver mais »			</a>
                                                </div>
                                            </article>
                                        @endforeach
                                    </div>
                                    {{--                                <nav class="elementor-pagination" role="navigation" aria-label="Pagination">--}}
                                    {{--                                    <span class="page-numbers prev">« Anterior</span>--}}
                                    {{--                                    <span aria-current="page" class="page-numbers current"><span class="elementor-screen-only">Page</span>1</span>--}}
                                    {{--                                    <a class="page-numbers" href="https://uemanet.uema.br/noticias/2/"><span class="elementor-screen-only">Page</span>2</a>--}}
                                    {{--                                    <a class="page-numbers" href="https://uemanet.uema.br/noticias/3/"><span class="elementor-screen-only">Page</span>3</a>--}}
                                    {{--                                    <span class="page-numbers dots">…</span>--}}
                                    {{--                                    <a class="page-numbers" href="https://uemanet.uema.br/noticias/200/"><span class="elementor-screen-only">Page</span>200</a>--}}
                                    {{--                                    <a class="page-numbers next" href="https://uemanet.uema.br/noticias/2/">Próxima »</a>		</nav>--}}
                                </div>
                            </div>
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
