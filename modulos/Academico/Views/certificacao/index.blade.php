@extends('layouts.modulos.academico')

@section('title')
    Certificação
@stop

@section('subtitle')
    Gerenciamento de registros
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
                <div class="form-group col-md-3">
                    {!! Form::label('crs_id', 'Curso*', ['class' => 'control-label']) !!}
                    {!! Form::select('crs_id', $cursos, null, ['class' => 'form-control', 'placeholder' => 'Escolha um curso']) !!}
                </div>
                <div class="form-group col-md-3">
                    {!! Form::label('ofc_id', 'Oferta*', ['class' => 'control-label']) !!}
                    {{ Form::select('ofc_id', [], null, ['class' => 'form-control', 'id' => 'ofc_id', 'value' => Input::get('ofc_id'), 'placeholder' => 'Oferta']) }}
                </div>
                <div class="form-group col-md-3">
                    {!! Form::label('trm_id', 'Turma*', ['class' => 'control-label']) !!}
                    {{ Form::select('trm_id', [], null, ['class' => 'form-control', 'id' => 'trm_id', 'value' => Input::get('trm_id'), 'placeholder' => 'Turma']) }}
                </div>
                <div class="form-group col-md-3">
                    {!! Form::label('mdo_id', 'Módulo*', ['class' => 'control-label']) !!}
                    {{ Form::select('mdo_id', [], null, ['class' => 'form-control', 'id' => 'mdo_id', 'value' => Input::get('pes_email'), 'placeholder' => 'Módulo']) }}
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
@stop

@section('scripts')
    <script src="{{url('/')}}/js/plugins/select2.js"></script>

    <script type="text/javascript">
        $(function () {
            $('select').select2();

            var selectCursos = $('#crs_id');
            var selectOfertas = $('#ofc_id');
            var selectTurmas = $('#trm_id');
            var selectModulos = $('#mdo_id');

            // Busca ofertas de curso
            $('#crs_id').change(function () {
                var curso = $(this).val();

                if (curso) {
                    selectOfertas.empty();
                    selectTurmas.empty();
                    selectModulos.empty();

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

                    $.harpia.httpget("{{url('/')}}/academico/async/cursos/findmodulosbycurso/" + curso)
                        .done(function (data) {
                            if (!$.isEmptyObject(data)) {
                                selectModulos.append('<option value="">Selecione um módulo</option>');
                                $.each(data, function (key, obj) {
                                    selectModulos.append("<option value='" + obj.mdo_id + "'>" + obj.mdo_nome + "</option>");
                                });
                            } else {
                                selectModulos.append('<option value="">Sem módulos cadastrados</option>');
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

            $('#mdo_id').change(function () {

            });
        });
    </script>
@stop