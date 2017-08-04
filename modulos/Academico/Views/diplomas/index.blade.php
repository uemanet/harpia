@extends('layouts.modulos.academico')

@section('title')
    Diplomas
@stop

@section('subtitle')
    Gerenciamento de impressão de diplomas
@stop

@section('stylesheets')
    <link rel="stylesheet" href="{{url('/')}}/css/plugins/select2.css">
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-filter"></i> Filtrar dados</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <div class="form-group col-md-4">
                    {!! Form::label('crs_id', 'Curso*', ['class' => 'control-label']) !!}
                    {!! Form::select('crs_id', $cursos, null, ['class' => 'form-control', 'placeholder' => 'Escolha um curso']) !!}
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('ofc_id', 'Oferta*', ['class' => 'control-label']) !!}
                    {{ Form::select('ofc_id', [], null, ['class' => 'form-control', 'id' => 'ofc_id', 'value' => Input::get('ofc_id'), 'placeholder' => 'Oferta']) }}
                </div>
                <div class="form-group col-md-4">
                    {!! Form::label('trm_id', 'Turma*', ['class' => 'control-label']) !!}
                    {{ Form::select('trm_id', [], null, ['class' => 'form-control', 'id' => 'trm_id', 'value' => Input::get('trm_id'), 'placeholder' => 'Turma']) }}
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <div class="box box-primary hidden" id="boxAlunos">
        <div class="box-header with-border">
            <h3 class="box-title">Lista de Alunos</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body"></div>
    </div>
@stop

@section('scripts')
    <script src="{{url('/')}}/js/plugins/select2.js"></script>

    <script type="text/javascript">
        $(function () {
            $('select').select2();

            selectCursos = $('#crs_id');
            selectOfertas = $('#ofc_id');
            selectTurmas = $('#trm_id');


            // Busca ofertas de curso
            $('#crs_id').change(function () {
                var curso = $(this).val();

                if (curso) {
                    selectOfertas.empty();
                    selectTurmas.empty();

                    $.harpia.httpget("{{url('/')}}/academico/async/ofertascursos/findallbycurso/" + curso)
                        .done(function (data) {
                            if (!$.isEmptyObject(data)) {
                                selectOfertas.append('<option value="">Selecione uma oferta</option>');
                                $.each(data, function (key, obj) {
                                    selectOfertas.append("<option value='" + obj.ofc_id + "'>" + obj.ofc_ano + " (" + obj.mdl_nome + ")</option>");
                                });
                            } else {
                                selectOfertas.append('<option value="">Sem ofertas cadastradas</option>');
                            }
                        });
                }
            });

            $('#ofc_id').change(function () {
                var oferta = $(this).val();

                if (oferta) {
                    selectTurmas.empty();

                    $.harpia.httpget("{{url('/')}}/academico/async/turmas/findallbyofertacurso/" + oferta)
                        .done(function (data) {
                            if (!$.isEmptyObject(data)) {
                                selectTurmas.append('<option value="">Selecione uma turma</option>');
                                $.each(data, function (key, obj) {
                                    selectTurmas.append("<option value='" + obj.trm_id + "'>" + obj.trm_nome + "</option>");
                                });
                            } else {
                                selectTurmas.append('<option value="">Sem turmas cadastradas</option>');
                            }
                        });

                }
            });

            $('#trm_id').change(function () {

                var turma = $('#trm_id').val();

                if (turma) {
                    $.harpia.httpget("{{url('/')}}/academico/async/diplomas/getalunosdiplomados/" + turma)
                        .done(function (data) {

                            renderTable(data);
                        });
                }
            });
            renderTable = function (data) {

              $('#boxAlunos').removeClass('hidden');

              var boxAlunos = $('#boxAlunos .box-body');
              boxAlunos.empty();

              if(!$.isEmptyObject(data)) {
                  var table = '';
                  table = '<div class="row">';
                  table += '<form action="{{route('academico.diplomas.imprimirdiplomas')}}" method="POST">';
                  table += '<div class="col-md-12">';
                  table += '{{csrf_field()}}';
                  table += '<table class="table table-bordered table-hover">';

                  table += '<tr>';
                  table += '<th width="1%"><label><input id="select_all" type="checkbox"></label></th>';
                  table += '<th width="1%">#</th>';
                  table += '<th>Aluno</th>';
                  table += '</tr>';

                  $.each(data, function (key, obj) {
                      table += '<tr>';
                      table += '<td><label><input type="checkbox" name="diplomas[]" class="diplomas" value="'+obj.dip_id+'"></label></td>';
                      table += '<td>'+obj.dip_id+'</td>';
                      table += '<td>'+obj.pes_nome+'</td>';
                      table += '</tr>';
                  });

                  table += '</table>';

                  table += "<div class='form-group'>";
                  table += "<button type='submit' class='btn btn-primary btnImprimirDiplomas hidden' formtarget='_blank'><i class='fa fa-file-pdf-o'></i> Imprimir Diplomas</button>";
                  table += "</div>";
                  table += "</div>";
                  table += "</form>";
                  table += "</div>";

                  boxAlunos.append(table);
                  hiddenButton();
              } else {
                  boxAlunos.append('<p>Sem registros para apresentar</p>');
              }

                $('#boxAlunos').removeClass('hidden');
                $('#boxAlunos .box-body').empty().append(table);
            }
        });

        // evento para selecionar todos os checkboxes
        $(document).on('click', '#select_all', function (event) {
            if (this.checked) {
                $(':checkbox').each(function () {
                    this.checked = true;
                });
            } else {
                $(':checkbox').each(function () {
                    this.checked = false;
                });
            }
        });

        var hiddenButton = function () {
            var checkboxes = $('#boxAlunos table td input[type="checkbox"]');

            if (checkboxes.is(':checked')) {
                $(document).find('.btnImprimirDiplomas').removeClass('hidden');
            } else {
                $(document).find('.btnImprimirDiplomas').addClass('hidden');
            }
        };

        $(document).on('click', '#boxAlunos table input[type="checkbox"]', hiddenButton);

    </script>
@stop
