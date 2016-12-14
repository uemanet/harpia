<div class="row">
    <div class="form-group col-md-3">
        {!! Form::label('crs_id', 'Curso*', ['class' => 'control-label']) !!}
        {!! Form::select('crs_id', $cursos, null, ['class' => 'form-control', 'placeholder' => 'Escolha um curso']) !!}
    </div>
    <div class="form-group col-md-3">
        {!! Form::label('ofc_id', 'Oferta do Curso*', ['class' => 'control-label']) !!}
        {!! Form::select('ofc_id', [], null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-md-3">
        {!! Form::label('trm_id', 'Turma*', ['class' => 'control-label']) !!}
        {!! Form::select('trm_id', [], null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-md-3">
        {!! Form::label('grp_id', 'Grupo*', ['class' => 'control-label']) !!}
        {!! Form::select('grp_id', [], null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="row">
    <div class="form-group col-md-3">
        {!! Form::label('tut_id', 'Tutor*', ['class' => 'control-label']) !!}
        {!! Form::select('tut_id', [], null, ['class' => 'form-control']) !!}
    </div>
    <div class="form-group col-md-3">
        {!! Form::label('date_ini', 'Data de início*', ['class' => 'control-label']) !!}
        {!! Form::text('date_ini', old('date_ini'), ['class' => 'form-control datepicker2', 'data-provide' => 'datepicker', 'date-date-format' => 'dd/mm/yyyy']) !!}
    </div>
    <div class="form-group col-md-3">
        {!! Form::label('date_fim', 'Data de fim*', ['class' => 'control-label']) !!}
        {!! Form::text('date_fim', old('date_fim'), ['class' => 'form-control datepicker', 'data-provide' => 'datepicker', 'date-date-format' => 'dd/mm/yyyy']) !!}
    </div>
    <div class="form-group col-md-3">
        <label class="control-label" style="visibility: hidden">Botão</label>
        <div class="controls">
            {!! Form::submit('Visualizar informações', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>

</div>

@section('scripts')
    @parent

    <script type="application/javascript">
        $(document).ready(function () {
            $('#crs_id').prop('selectedIndex', 0);
        });
    </script>
    <script type="application/javascript">

        $('#crs_id').change(function (e) {
            var crsId = $(this).val();

            var selectOfertas = $('#ofc_id');
            var selectTurmas = $('#trm_id');
            if (crsId) {

                // Populando o select de ofertas de cursos
                selectOfertas.empty();
                selectTurmas.empty();

                $.harpia.httpget("{{url('/')}}/academico/async/ofertascursos/findallbycurso/" + crsId)
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

                $.harpia.httpget('{{url("/")}}/academico/async/turmas/findallbyofertacurso/' + ofertaId)
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

                $.harpia.httpget('{{url("/")}}/academico/async/grupos/findallbyturma/' + turmaId)
                        .done(function (data) {
                            if (!$.isEmptyObject(data)) {
                                selectGrupos.append('<option>Selecione o grupo</option>');
                                $.each(data, function (key, obj) {
                                    selectGrupos.append('<option value="' + obj.grp_id + '">' + obj.grp_nome + '</option>')
                                });
                            } else {
                                selectGrupos.append('<option>Sem grupos cadastrados</option>')
                            }
                        });
            }

        })

        $('#grp_id').change(function (e) {
            var grupoId = $(this).val();
            var selectTutores = $('#tut_id');

            if (grupoId) {
                selectTutores.empty();

                $.harpia.httpget('{{url("/")}}/academico/async/tutores/findallbygrupo/' + grupoId)
                        .done(function (data) {
                            if (!$.isEmptyObject(data)) {
                                selectTutores.append('<option>Selecione o tutor</option>');
                                $.each(data, function (key, obj) {
                                    selectTutores.append('<option value="' + obj.tut_id + '">' + obj.pes_nome + '</option>')
                                });
                            } else {
                                selectTutores.append('<option>Sem tutores cadastrados nesse grupo</option>')
                            }
                        });
            }

        })

    </script>
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("select").select2();
        });

        $(document).on('click', '.btn-primary', function (event) {
            event.preventDefault();

            var grafico = $('#grafico');
            grafico.empty();
            grafico.append('<canvas id="grafico-tempo" height="400"></canvas>');

            var tutor = $('#tut_id').find(":selected").val();
            var datainicio = $('#date_ini').val().replace(/\//g, "\-");
            var datafim = $('#date_fim').val().replace(/\//g, "\-");
            var token = '{{$ambiente->asr_token}}';
            var timeclicks = {{$timeclicks}};
            var moodlewsformat = "json";
            var wsfunction = "{{$wsfunction}}";
            var url = "{{$ambiente->amb_url}}";

            var request = $.ajax({
                url: url + "webservice/rest/server.php?wstoken=" + token + "&wsfunction=" + wsfunction + "&start_date=" + datainicio + "&end_date=" + datafim + "&tutor_id=" + 2 + "&time_between_clicks=" + timeclicks + "&moodlewsrestformat=" + moodlewsformat,
                type: "POST",

                dataType: "json",
                success: function (moodledata) {
                    $.harpia.hideloading();

                    if (moodledata.errorcode === "startdateerror") {
                        toastr.error('A data de fim não deve ser menor que a data de início', null, {progressBar: true});
                    }

                    if (moodledata.errorcode === "enddateerror") {
                        toastr.error('A data de fim não deve maior que o dia atual', null, {progressBar: true});
                    }

                    var dias = new Array();
                    var tempos = new Array();

                    for (var i = 0; i < moodledata.items.length; i++) {
                        dias[i] = moodledata.items[i].date.replace(/-/g, "\/");
                        tempos[i] = Math.floor(moodledata.items[i].onlinetime / 3600);
                    }

                    var config;
                    config = {
                        type: 'line',
                        data: {
                            labels: dias,
                            datasets: [{
                                label: moodledata.fullname,
                                data: tempos,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            title: {
                                display: true,
                                text: 'Gráfico de Acesso ao AVA'
                            },
                            hover: {
                                mode: 'nearest',
                                intersect: true
                            },
                            scales: {
                                xAxes: [{
                                    display: true,
                                    beginAtZero: true,
                                    scaleLabel: {
                                        display: false,
                                        labelString: 'Dias'
                                    }
                                }],
                                yAxes: [{
                                    display: true,
                                    beginAtZero: true,
                                    ticks: {
                                        suggestedMax: 6,
                                        fixedStepSize: 1
                                    },
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Tempo Online (Horas)',
                                        stacked: true
                                    }
                                }]
                            },
                            tooltips: {
                                mode: 'index',
                                intersect: false,
                                bodyFontColor: "#fff",
                                bodyFontStyle: "bold",
                                bodyFontFamily: "'Helvetica', 'Arial', sans-serif",
                                footerFontSize: 15,
                                callbacks: {
                                    label: function (tooltipItem, data) {
                                        var value = data.datasets[0].data[tooltipItem.index];
                                        seconds = moodledata.items[tooltipItem.index].onlinetime;
                                        var h = Math.floor(seconds / 3600);
                                        var m = Math.floor(seconds % 3600 / 60);
                                        var s = Math.floor(seconds % 3600 % 60);
                                        var humanFormat = h + 'h:' + m + 'm:' + s + 's';
                                        return humanFormat;
                                    },
                                },
                            }
                        }
                    };

                    // get line chart canvas
                    var monitoramento = document.getElementById('grafico-tempo').getContext('2d');
                    // draw line chart
                    new Chart(monitoramento, config);

                },
                error: function (error) {
                    $.harpia.hideloading();
                    toastr.error('Erro ao tentar se comunicar com o Ambiente Virtual.', null, {progressBar: true});

                }
            });
        });


    </script>
@stop