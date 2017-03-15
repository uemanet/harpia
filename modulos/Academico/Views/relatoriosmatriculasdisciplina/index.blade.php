@extends('layouts.modulos.academico')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection

@section('title')
    Relatório de Alunos por Disciplina
@endsection

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
            <div class="box-body">
                <form id="form" method="GET" action="">
                    <div class="row">
                        <div class="col-md-4 @if ($errors->has('crs_id')) has-error @endif">
                            {!! Form::label('crs_id', 'Curso*') !!}
                            <div class="form-group">
                                {!! Form::select('crs_id', $cursos, Input::get('crs_id'), ['class' => 'form-control', 'placeholder' => 'Escolha o Curso']) !!}
                                @if ($errors->has('crs_id')) <p class="help-block">{{ $errors->first('crs_id') }}</p> @endif
                            </div>
                        </div>
                        <div class="col-md-4 @if ($errors->has('ofc_id')) has-error @endif">
                            {!! Form::label('ofc_id', 'Oferta de Curso*') !!}
                            <div class="form-group">
                                {!! Form::select('ofc_id', $ofertasCurso, Input::get('ofc_id'), ['class' => 'form-control']) !!}
                                @if ($errors->has('ofc_id')) <p class="help-block">{{ $errors->first('ofc_id') }}</p> @endif
                            </div>
                        </div>
                        <div class="col-md-4 @if ($errors->has('trm_id')) has-error @endif">
                            {!! Form::label('trm_id', 'Turma*') !!}
                            <div class="form-group">
                                {!! Form::select('trm_id', $turmas, Input::get('trm_id'), ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 @if ($errors->has('per_id')) has-error @endif">
                            {!! Form::label('per_id', 'Período Letivo*') !!}
                            <div class="form-group">
                                {!! Form::select('per_id', $periodos, Input::get('per_id'), ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-4 @if ($errors->has('ofd_id')) has-error @endif">
                            {!! Form::label('ofd_id', 'Disciplinas Ofertadas*') !!}
                            <div class="form-group">
                                {!! Form::select('ofd_id', $disciplinas, Input::get('ofd_id'), ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-2 @if ($errors->has('mof_situacao_matricula')) has-error @endif">
                            {!! Form::label('mof_situacao_matricula', 'Situação da matricula') !!}
                            <div class="form-group">
                                {!! Form::select('mof_situacao_matricula', ["todos" => "Todos",
                                    "cursando" => "Cursando",
                                    "aprovado_media" => "Aprovado por Média",
                                    "aprovado_final" => "Aprovado por Final",
                                    "reprovado_media" => "Reprovado por Média",
                                    "reprovado_final" => "Reprovado por Final",
                                    "cancelado" => "Cancelado"
                                ], Input::get('mof_situacao_matricula'), ['class' => 'form-control', 'placeholder' => 'Selecione o status']) !!}
                            </div>
                        </div>
                        <div class="col-md-1">
                            <label for="">&nbsp;</label>
                            <div class="form-group">
                                <input type="submit" id="btnBuscar" class="form-control btn-primary" value="Buscar">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.box-body -->
    </div>

    @if(!is_null($tabela))
        <div class="box box-primary">
            <div class="box-header">
                <div class="pull-right box-tools">
                    <button id="formPdf" type="button" class="btn btn-success" >
                        <i class="fa fa-file-pdf-o"></i> Exportar para PDF</button>
                </div>
            </div>
            <div class="box-body">
                {!! $tabela->render() !!}
            </div>
        </div>

        <div class="text-center">{!! $paginacao->links() !!}</div>

    @else
        <div class="box box-primary">
            <div class="box-body">Sem registros para apresentar</div>
        </div>
    @endif
@endsection

@section('scripts')
    <script src="{{url('/')}}/js/plugins/select2.js"></script>

    <script type="text/javascript">

        $(function () {
            // select2
            $('select').select2();

            var ofertasCursoSelect = $('#ofc_id');
            var turmaSelect = $('#trm_id');
            var periodosLetivosSelect = $('#per_id');
            var disciplinasOfertadasSelect = $('#ofd_id');
            var btnBuscar = $('#btnBuscar');
            var routePdf = "{{ route('academico.relatoriosmatriculasdisciplinas.pdf') }}";
            var routeIndex = "{{ route('academico.relatoriosmatriculasdisciplinas.index') }}";

            // evento change select de cursos
            $('#crs_id').change(function () {

                // limpando selects
                ofertasCursoSelect.empty();
                turmaSelect.empty();
                periodosLetivosSelect.empty();
                disciplinasOfertadasSelect.empty();

                // buscar as ofertas de curso de acordo com o curso escolhido
                var cursoId = $(this).val();

                if (!cursoId || cursoId == '') {
                    return false;
                }

                $.harpia.httpget("{{url('/')}}/academico/async/ofertascursos/findallbycurso/" + cursoId).done(function (response) {
                    if (!$.isEmptyObject(response)) {
                        ofertasCursoSelect.append("<option value=''>Selecione a oferta</option>");
                        $.each(response, function (key, obj) {
                            ofertasCursoSelect.append('<option value="' + obj.ofc_id + '">' + obj.ofc_ano + ' (' + obj.mdl_nome + ')</option>');
                        });
                    } else {
                        ofertasCursoSelect.append("<option>Sem ofertas disponiveis</option>");
                    }
                });
            });

            // evento change select de ofertas de curso
            ofertasCursoSelect.change(function () {

                //limpando selects
                turmaSelect.empty();
                periodosLetivosSelect.empty();
                disciplinasOfertadasSelect.empty();

                // buscar as turmas de acordo com a oferta de curso
                var ofertaCursoId = $(this).val();

                if (!ofertaCursoId || ofertaCursoId == '') {
                    return false;
                }

                $.harpia.httpget("{{url('/')}}/academico/async/turmas/findallbyofertacurso/" + ofertaCursoId).done(function (response) {
                    if (!$.isEmptyObject(response)) {
                        turmaSelect.append('<option value="">Selecione a turma</option>');
                        $.each(response, function (key, obj) {
                            turmaSelect.append('<option value="' + obj.trm_id + '">' + obj.trm_nome + '</option>');
                        });
                    } else {
                        turmaSelect.append('<option>Sem turmas disponíveis</option>');
                    }
                });
            });

            // evento change select de turmas
            turmaSelect.change(function () {

                // limpando selects
                periodosLetivosSelect.empty();
                disciplinasOfertadasSelect.empty();

                // buscar os periodos letivos de acordo com a turma escolhida
                var turmaId = $(this).val();

                if (!turmaId || turmaId == '') {
                    return false;
                }

                $.harpia.httpget("{{url('/')}}/academico/async/periodosletivos/findallbyturma/" + turmaId).done(function (response) {
                    if (!$.isEmptyObject(response)) {
                        periodosLetivosSelect.append('<option value="">Selecione o periodo letivo</option>');
                        $.each(response, function (key, obj) {
                            periodosLetivosSelect.append('<option value="' + obj.per_id + '">' + obj.per_nome + '</option>');
                        });
                    } else {
                        periodosLetivosSelect.append('<option>Sem periodos letivos disponiveis</option>');
                    }
                });
            });

            //evento change select de periodos letivos
            periodosLetivosSelect.change(function () {

                // limpando select
                disciplinasOfertadasSelect.empty();

                // buscar todas as disciplinas ofertadas de acordo com o periodo e a turma
                var turmaId = turmaSelect.val();
                var periodoLetivoId = $(this).val();

                if ((!turmaId || turmaId == '') || (!periodoLetivoId || periodoLetivoId == '')) {
                    return false;
                }

                $.harpia.httpget("{{url('/')}}/academico/async/ofertasdisciplinas/findall?ofd_trm_id=" + turmaId + "&ofd_per_id=" + periodoLetivoId).done(function (response) {
                    if (!$.isEmptyObject(response)) {
                        disciplinasOfertadasSelect.append('<option value="">Selecione a disciplina ofertada</option>');
                        $.each(response, function (key, obj) {
                            disciplinasOfertadasSelect.append('<option value="' + obj.ofd_id + '">' + obj.dis_nome + '</option>');
                        });
                    } else {
                        disciplinasOfertadasSelect.append('<option>Sem disciplinas ofertadas disponíveis</option>');
                    }
                })
            });

            $(document).on('click', '#btnBuscar', function () {
                $('#form').attr('action', routeIndex).removeAttr('target', '_blank').submit();
            });

            $(document).on('click', '#formPdf', function () {
                $('#form').attr('action', routePdf).attr('target', '_blank').submit();
            });

        });
    </script>
@stop
