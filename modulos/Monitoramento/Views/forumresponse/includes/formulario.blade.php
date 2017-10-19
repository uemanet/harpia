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
        {!! Form::label('grp_id', 'Tipo de Tutoria*', ['class' => 'control-label']) !!}
        {!! Form::select('grp_id', [], null, ['class' => 'form-control']) !!}
    </div>
</div>
<div class="row">
    <div class="form-group col-md-3">
        {!! Form::label('tut_id', 'Tutor*', ['class' => 'control-label']) !!}
        {!! Form::select('tut_id', [], null, ['class' => 'form-control', 'multiple' => 'multiple']) !!}
    </div>
    <div class="form-group col-md-3">
        {!! Form::label('date_ini', 'Data de início*', ['class' => 'control-label']) !!}
        {!! Form::text('date_ini', old('date_ini'), ['class' => 'form-control datepicker2', 'data-provide' => 'datepicker', 'date-date-format' => 'dd/mm/yyyy']) !!}
    </div>
    <div class="form-group col-md-3">
        {!! Form::label('date_fim', 'Data de fim*', ['class' => 'control-label']) !!}
        {!! Form::text('date_fim', old('date_fim'), ['class' => 'form-control datepicker', 'data-provide' => 'datepicker', 'date-date-format' => 'dd/mm/yyyy']) !!}
    </div>
    <div class="form-group col-md-1">
        <label for="" class="control-label"></label>
        <button class="btn btn-primary form-control" id="btnLocalizar"><i class="fa fa-search"></i></button>
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
<script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $("select").select2();
    });

    $('#btnLocalizar').click(function () {
        event.preventDefault();

        var token = '{{$ambiente->asr_token}}';
        var moodlewsformat = "json";
        var wsfunction = "monitor_tutor_answers";
        var url = "{{$ambiente->amb_url}}";
        // console.log(url);
        $.harpia.showloading();
            var request = $.ajax({
                url: url + "webservice/rest/server.php?wstoken=" + token + "&wsfunction=" + wsfunction + "&pes_id=" + 12794 +  "&trm_id=" + 32 +  "&moodlewsrestformat=" + moodlewsformat,
                type: "POST",
                dataType: "json",
                async: false,
                success: function (moodledata) {
                    $.harpia.hideloading();
                    // console.log(moodledata);
                    renderTable(moodledata);
                },
                error: function (error) {
                    $.harpia.hideloading();
                    // console.log(error);
                    toastr.error('Erro ao tentar se comunicar com o Ambiente Virtual.', null, {progressBar: true});
                }
            });
    })

    renderTable = function (moodledata) {
      html = '';
      var grupoatual = console.log(moodledata.itens[0].grupo);
      $.each(moodledata.itens, function (chave, objeto) {


         html += '<div class="row"><div class="col-md-12">';
         html += '<div class="box">';
            html += '<div class="box-header">';
              html += '<h3 class="box-title">'+grupoatual+'</h3>';
            html += '</div>';
            html += '<div class="box-body no-padding">';
              html += '<table class="table table-condensed">';
                html += '<tr>';
                  html += '<th style="width: 10px">#</th>';
                  html += '<th style="width: 30%">Discussão</th>';
                  html += '<th>Participação</th>';
                  html += '<th style="width: 20%">Porcentagem de respostas</th>';
                html += '</tr>';
                $.each(moodledata.itens, function (key, obj) {

                  if (obj.grupo != grupoatual){
                    grupoatual = obj.grupo;
                    return false;
                  }

                  html += '<tr>';
                  html += '<td>1.</td>';
                  html += '<td>'+obj.discussion+'</td>';
                  html += '<td>';
                  html += '<div class="progress progress-xs">';

                  if (obj.percentual > 0.7) {
                    html += '<div class="progress-bar progress-bar-success" style="width: '+obj.percentual*100+'%"></div>';

                  } else if(obj.percentual>0.4 && obj.percentual<0.7) {
                    html += '<div class="progress-bar progress-bar-yellow" style="width: '+obj.percentual*100+'%"></div>';
                  } else {
                    html += '<div class="progress-bar progress-bar-danger" style="width: '+obj.percentual*100+'%"></div>';
                  }

                  html += '</div>';
                  html += '</td>';
                  if (obj.percentual > 0.7) {
                    html += '<td><span class="badge bg-green">'+(obj.percentual*100).toPrecision(2)+'%</span></td>';

                  } else if(obj.percentual>0.4 && obj.percentual<0.7) {
                    html += '<td><span class="badge bg-yellow">'+(obj.percentual*100).toPrecision(2)+'%</span></td>';
                  }else {
                    html += '<td><span class="badge bg-red">'+(obj.percentual*100).toPrecision(2)+'%</span></td>';

                  }
                  html += '</tr>';

                });

              html += '</table>';
            html += '</div>';
          html += '</div>';
          });
                $('#boxAlunos').removeClass('hidden');
                $('#boxAlunos .box-body').empty().append(html);
    }


</script>
@stop
