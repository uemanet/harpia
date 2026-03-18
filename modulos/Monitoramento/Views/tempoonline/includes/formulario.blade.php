<div class="row">
    <div class="form-group col-md-3">
        <label for="crs_id" class="control-label">Curso*</label>
        <select name="crs_id" id="crs_id" class="form-control">
            <option value="">Escolha um curso</option>
            @foreach($cursos as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-3">
        <label for="ofc_id" class="control-label">Oferta do Curso*</label>
        <select name="ofc_id" id="ofc_id" class="form-control"></select>
    </div>
    <div class="form-group col-md-3">
        <label for="trm_id" class="control-label">Turma*</label>
        <select name="trm_id" id="trm_id" class="form-control"></select>
    </div>
    <div class="form-group col-md-3">
        <label for="grp_id" class="control-label">Tipo de Tutoria*</label>
        <select name="grp_id" id="grp_id" class="form-control"></select>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-3">
        <label for="tut_id" class="control-label">Tutor*</label>
        <select name="tut_id" id="tut_id" class="form-control" multiple="multiple"></select>
    </div>
    <div class="form-group col-md-3">
        <label for="date_ini" class="control-label">Data de início*</label>
        <input type="text" name="date_ini" id="date_ini" value="{{ old('date_ini') }}" class="form-control datepicker2" data-provide="datepicker" date-date-format="dd/mm/yyyy" >
    </div>
    <div class="form-group col-md-3">
        <label for="date_fim" class="control-label">Data de fim*</label>
        <input type="text" name="date_fim" id="date_fim" value="{{ old('date_fim') }}" class="form-control datepicker" data-provide="datepicker" date-date-format="dd/mm/yyyy" >
    </div>
    <div class="form-group col-md-3">
        <label class="control-label" style="visibility: hidden">Botão</label>
        <div class="controls">
            <button type="submit" class="btn btn-primary">Visualizar informações</button>
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

                $.harpia.httpget('{{url("/")}}/academico/async/turmas/findallbyofertacursointegrada/' + ofertaId)
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
                $.harpia.httpget('{{url("/")}}/academico/async/tutores/findallbyturmatipotutoria/' + turmaId + '/' + tipotutoria)
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
    </script>

    <script type="text/javascript">
        $(document).on('click', '.btn-primary', function (event) {
            event.preventDefault();

            var array = new Array();

            var tutor = $('#tut_id').val();

            for (var i = 0; i < tutor.length; i++) {
                array.push(parseInt(tutor[i]));
            }

            var datainicio = $('#date_ini').val().replace(/\//g, "\-");
            var datafim = $('#date_fim').val().replace(/\//g, "\-");
            var token = '{{$monitoramento->asr_token}}';
            var timeclicks = {{$timeclicks}};
            var moodlewsformat = "json";
            var wsfunction = "{{$wsfunction}}";
            var url = "{{$ambiente->amb_url}}";

            var dadosgrafico = new Array();

            for (var i = 0; i < tutor.length; i++) {
                if (i === 0) {

                    var grafico = $('#grafico');
                    grafico.empty();
                    grafico.append('<canvas id="grafico-tempo" height="400"></canvas>');
                }

                var request = $.ajax({
                    url: url + "webservice/rest/server.php?wstoken=" + token + "&wsfunction=" + wsfunction + "&startdate=" + datainicio + "&enddate=" + datafim + "&pesid=" + array[i] + "&timebetweenclicks=" + timeclicks + "&moodlewsrestformat=" + moodlewsformat,
                    type: "POST",
                    dataType: "json",
                    async: false,
                    success: function (moodledata) {
                        $.harpia.hideloading();

                        if (moodledata.errorcode === "startdateerror") {
                            toastr.error('A data de fim não deve ser menor que a data de início', null, {progressBar: true});
                        }

                        if (moodledata.errorcode === "enddateerror") {
                            toastr.error('A data de fim não deve maior que o dia atual', null, {progressBar: true});
                        }
                        dadosgrafico.push(moodledata);
                    },
                    error: function (error) {
                        $.harpia.hideloading();
                        toastr.error('Erro ao tentar se comunicar com o Ambiente Virtual.', null, {progressBar: true});
                    }
                });
            }

            dadosDatasets = new Array();

            var dias = new Array();
            var tempos = new Array();
            var parada = new Array();

            for (var i = 0; i < dadosgrafico.length; i++) {

                parada = dadosgrafico[i].items;

                for (var j = 0; j < parada.length; j++) {
                    dias[j] = parada[j].date.replace(/-/g, "\/");
                    tempos[j] = parada[j].onlinetime;
                }

                dataset = {
                    label: dadosgrafico[i].fullname,
                    data: tempos,
                    fill: true,
                    backgroundColor: 'rgba(255, ' + Math.floor((Math.random() * 255) + 1) + ', ' + Math.floor((Math.random() * 255) + 1) + ', .6)'
                }
                tempos = [];

                dadosDatasets.push(dataset);
            }

            var config;
            config = {
                type: 'line',
                data: {
                    labels: dias,
                    datasets: dadosDatasets
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
                            // ticks: {
                            //   suggestedMax: 6,
                            //   fixedStepSize: 1
                            // },
                            scaleLabel: {
                                display: true,
                                labelString: 'Tempo Online (Segundos)',
                                stacked: true
                            }
                        }]
                    },
                    tooltips: {

                        mode: 'label',

                        intersect: false,
                        bodyFontColor: "#fff",
                        bodyFontStyle: "bold",
                        bodyFontFamily: "'Helvetica', 'Arial', sans-serif",
                        footerFontSize: 15,
                        // callbacks: {
                        //     label: function (tooltipItem, data) {
                        //
                        //         for(var i = 0; i < data.datasets.lenght; i++){
                        //             var moodle = dadosgrafico[i];
                        //             var seconds = moodle.items[tooltipItem.index].onlinetime;
                        //
                        //             var h = Math.floor(seconds / 3600);
                        //             var m = Math.floor(seconds % 3600 / 60);
                        //             var s = Math.floor(seconds % 3600 % 60);
                        //             var humanFormat = h + 'h:' + m + 'm:' + s + 's';
                        //
                        //             return humanFormat;
                        //         }
                        //     },
                        // },
                    }
                }
            };

            // get line chart canvas
            var monitoramento = document.getElementById('grafico-tempo').getContext('2d');
            // draw line chart

            new Chart(monitoramento, config);
        });


    </script>
@stop
