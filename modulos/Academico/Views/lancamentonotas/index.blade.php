@extends('layouts.modulos.academico')

@section('title')
    Lançamento de notas
@stop

@section('subtitle')
    Gerenciamento de notas em matrículas de disciplinas
@stop

@section('stylesheets')
    <link rel="stylesheet" href="{{url('/')}}/css/plugins/select2.css">
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-search"></i> Buscar Ofertas de Disciplinas</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body">
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
                <div class="form-group col-md-2">
                    {!! Form::label('per_id', 'Período Letivo*', ['class' => 'control-label']) !!}
                    {!! Form::select('per_id', [], null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group col-md-1">
                    <label for="" class="control-label"></label>
                    <button class="btn btn-primary form-control" id="btnLocalizar"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>

    <div class="table-ofertas"></div>
@stop

@section('scripts')
    <script src="{{url('/')}}/js/plugins/select2.js"></script>

    <script>
        $(function () {
            $('select').select2();

            var selectOfertas = $('#ofc_id');
            var selectTurmas = $('#trm_id');
            var selectPeriodos = $("#per_id");

            var boxDisciplinas = $('#boxDisciplinas');

            // populando o select de ofertas de curso
            $('#crs_id').change(function () {
                var curso = $(this).val();

                if (curso) {
                    selectOfertas.empty();
                    selectTurmas.empty();
                    selectPeriodos.empty();

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

            // populando o select de turmas
            selectOfertas.change(function () {
                var oferta = $(this).val();

                if (oferta) {
                    selectTurmas.empty();
                    selectPeriodos.empty();

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

            selectTurmas.change(function () {
                var turmaId = $(this).val();

                if (turmaId) {
                    // limpando selects
                    selectPeriodos.empty();
                    $.harpia.httpget("{{url('/')}}/academico/async/periodosletivos/findallbyturma/" + turmaId)
                        .done(function (response) {
                            if (!$.isEmptyObject(response)) {
                                selectPeriodos.append("<option value=''>Selecione um periodo</option>");
                                $.each(response, function (key, obj) {
                                    selectPeriodos.append("<option value='" + obj.per_id + "'>" + obj.per_nome + "</option>");
                                });
                            } else {
                                selectPeriodos.append("<option value=''>Sem períodos disponíveis</option>");
                            }
                        });
                }
            });

            // evento click no botão de pesquisar ofertas
            $('#btnLocalizar').click(function () {
                var turma = selectTurmas.val();
                var periodo = selectPeriodos.val();

                if (turma == '' || periodo == '') {
                    return false;
                }

                var url = "{{ route('academico.async.ofertasdisciplinas.findall') }}?"+
                    "ofd_trm_id=" + turma + "&ofd_per_id=" + periodo;

                $.ajax({
                    method: 'GET',
                    url: url,
                    success: function(response) {
                        console.log(response);
                    },
                    error: function(response) {
                        $.harpia.hideloading();
                        toastr.error('Erro ao processar requisição. Entrar em contato com o suporte.', null, {progressBar: true});
                    }
                });

            });
        });
    </script>
@stop