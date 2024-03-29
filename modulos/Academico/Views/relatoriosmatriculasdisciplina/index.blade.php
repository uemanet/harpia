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
                                {!! Form::select('crs_id', $cursos, Request::input('crs_id'), ['class' => 'form-control', 'placeholder' => 'Escolha o Curso']) !!}
                                @if ($errors->has('crs_id')) <p class="help-block">{{ $errors->first('crs_id') }}</p> @endif
                            </div>
                        </div>
                        <div class="col-md-4 @if ($errors->has('ofc_id')) has-error @endif">
                            {!! Form::label('ofc_id', 'Oferta de Curso*') !!}
                            <div class="form-group">
                                {!! Form::select('ofc_id', $ofertasCurso, Request::input('ofc_id'), ['class' => 'form-control']) !!}
                                @if ($errors->has('ofc_id')) <p class="help-block">{{ $errors->first('ofc_id') }}</p> @endif
                            </div>
                        </div>
                        <div class="col-md-4 @if ($errors->has('trm_id')) has-error @endif">
                            {!! Form::label('trm_id', 'Turma*') !!}
                            <div class="form-group">
                                {!! Form::select('trm_id', $turmas, Request::input('trm_id'), ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 @if ($errors->has('per_id')) has-error @endif">
                            {!! Form::label('per_id', 'Período Letivo*') !!}
                            <div class="form-group">
                                {!! Form::select('per_id', $periodos, Request::input('per_id'), ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-3 @if ($errors->has('ofd_id')) has-error @endif">
                            {!! Form::label('ofd_id', 'Disciplinas Ofertadas*') !!}
                            <div class="form-group">
                                {!! Form::select('ofd_id', $disciplinas, Request::input('ofd_id'), ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-3 @if ($errors->has('pol_id')) has-error @endif">
                            {!! Form::label('pol_id', 'Polo') !!}
                            <div class="form-group">
                                {!! Form::select('pol_id', $polos, Request::input('pol_id'), ['class' => 'form-control', 'placeholder' => 'Selecione o polo']) !!}
                                @if ($errors->has('pol_id')) <p class="help-block">{{ $errors->first('pol_id') }}</p> @endif
                            </div>
                        </div>
                        <div class="col-md-2 @if ($errors->has('mof_situacao_matricula')) has-error @endif">
                            {!! Form::label('mof_situacao_matricula', 'Situação') !!}
                            <div class="form-group">
                                {!! Form::select('mof_situacao_matricula', $situacao, Request::input('mof_situacao_matricula'), ['class' => 'form-control']) !!}
                                @if ($errors->has('mof_situacao_matricula')) <p class="help-block">{{ $errors->first('mof_situacao_matricula') }}</p> @endif
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for=""></label>
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
                <div class="row">
                    <div class="col-md-2" style="float: right; margin-right: 1%;">
                        <form id="exportPdf" target="_blank" method="post" action="{{ route('academico.relatoriosmatriculasdisciplinas.xls') }}">
                            {!! ActionButton::grid([
                                    'type' => 'LINE',
                                    'buttons' => [
                                        [
                                        'classButton' => 'btn btn-success',
                                        'icon' => 'fa fa-file-excel-o',
                                        'route' => 'academico.relatoriosmatriculasdisciplinas.xls',
                                        'label' => 'Exportar para XLS',
                                        'method' => 'post',
                                        'id' => '',
                                        'attributes' => ['id' => 'formPdf','target' => '_blank']
                                        ]
                                    ]
                            ]) !!}
                            <input type="hidden" name="trm_id" id="turmaId" value="">
                            <input type="hidden" name="crs_id" id="cursoId" value="">
                            <input type="hidden" name="ofc_id" id="ofertaCursoId" value="">
                            <input type="hidden" name="pol_id" id="poloId" value="">
                            <input type="hidden" name="per_id" id="periodoId" value="">
                            <input type="hidden" name="ofd_id" id="ofertaDisciplinaId" value="">
                            <input type="hidden" name="mof_situacao_matricula" id="situacao" value="">
                        </form>
                    </div>
                    <div class="col-md-2" style="float: right;">
                        <form id="exportPdf" target="_blank" method="post" action="{{ route('academico.relatoriosmatriculasdisciplinas.pdf') }}">
                            {!! ActionButton::grid([
                                    'type' => 'LINE',
                                    'buttons' => [
                                        [
                                        'classButton' => 'btn btn-danger',
                                        'icon' => 'fa fa-file-pdf-o',
                                        'route' => 'academico.relatoriosmatriculasdisciplinas.pdf',
                                        'label' => 'Exportar para PDF',
                                        'method' => 'post',
                                        'id' => '',
                                        'attributes' => ['id' => 'formPdf','target' => '_blank']
                                        ]
                                    ]
                            ]) !!}
                            <input type="hidden" name="trm_id" id="pturmaId" value="">
                            <input type="hidden" name="crs_id" id="pcursoId" value="">
                            <input type="hidden" name="ofc_id" id="pofertaCursoId" value="">
                            <input type="hidden" name="pol_id" id="ppoloId" value="">
                            <input type="hidden" name="per_id" id="pperiodoId" value="">
                            <input type="hidden" name="ofd_id" id="pofertaDisciplinaId" value="">
                            <input type="hidden" name="mof_situacao_matricula" id="psituacao" value="">
                        </form>
                    </div>
                </div>
            </div>
            <div class="box-body">
                {!! $tabela->render() !!}
            </div>
        </div>

        <div class="text-center">{!! $paginacao->links('pagination::bootstrap-4') !!}</div>

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
            var cursoSelect = $('#crs_id');
            var turmaSelect = $('#trm_id');
            var polosSelect = $('#pol_id');
            var periodosLetivosSelect = $('#per_id');
            var disciplinasOfertadasSelect = $('#ofd_id');
            var btnBuscar = $('#btnBuscar');
            var routeIndex = "{{ route('academico.relatoriosmatriculasdisciplinas.index') }}";
            var situacaoSelect = $('#mof_situacao_matricula');

            // evento change select de cursos
            $('#crs_id').change(function () {

                // limpando selects
                ofertasCursoSelect.empty();
                turmaSelect.empty();
                polosSelect.empty();
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
                polosSelect.empty();

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

                // buscar polos
                $.harpia.httpget("{{url('/')}}/academico/async/polos/findallbyofertacurso/" + ofertaCursoId).done(function (response) {
                    if(!$.isEmptyObject(response)) {
                        polosSelect.append("<option value=''>Selecione um polo</option>");

                        $.each(response, function (key, obj) {
                            polosSelect.append("<option value='"+obj.pol_id+"'>"+obj.pol_nome+"</option>");
                        });
                    } else {
                        polosSelect.append("<option value=''>Sem polos cadastrados</option>");
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

            $('#turmaId').attr('value', turmaSelect.val());
            $('#ofertaCursoId').attr('value', ofertasCursoSelect.val());
            $('#poloId').attr('value', polosSelect.val());
            $('#cursoId').attr('value', cursoSelect.val());
            $('#periodoId').attr('value', periodosLetivosSelect.val());
            $('#ofertaDisciplinaId').attr('value', disciplinasOfertadasSelect.val());
            $('#situacao').attr('value', situacaoSelect.val());

            $('#pturmaId').attr('value', turmaSelect.val());
            $('#pofertaCursoId').attr('value', ofertasCursoSelect.val());
            $('#ppoloId').attr('value', polosSelect.val());
            $('#pcursoId').attr('value', cursoSelect.val());
            $('#pperiodoId').attr('value', periodosLetivosSelect.val());
            $('#pofertaDisciplinaId').attr('value', disciplinasOfertadasSelect.val());
            $('#psituacao').attr('value', situacaoSelect.val());

            $(document).on('click', '#formPdf', function (event) {
                $('#exportPdf').submit();
            });

        });
    </script>
@stop
