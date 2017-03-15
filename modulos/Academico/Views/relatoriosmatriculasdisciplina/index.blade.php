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
                <form method="GET" action="{{ route('academico.relatoriosmatriculasdisciplinas.index') }}">

                    <div class="row">
                        <div class="col-md-4">
                            {!! Form::label('crs_id', 'Curso*') !!}
                            <div class="form-group">
                                {!! Form::select('crs_id', $cursos, null, ['class' => 'form-control', 'placeholder' => 'Escolha o Curso']) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            {!! Form::label('ofc_id', 'Oferta de Curso*') !!}
                            <div class="form-group">
                                {!! Form::select('ofc_id', [], null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            {!! Form::label('trm_id', 'Turma*') !!}
                            <div class="form-group">
                                {!! Form::select('trm_id', [], null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            {!! Form::label('per_id', 'Período Letivo*') !!}
                            <div class="form-group">
                                {!! Form::select('per_id', [], null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            {!! Form::label('ofd_id', 'Disciplinas Ofertadas*') !!}
                            <div class="form-group">
                                {!! Form::select('ofd_id', [], null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-2">
                            {!! Form::label('mof_situacao_matricula', 'Situação da matricula') !!}
                            <div class="form-group">
                                {!! Form::select('mof_situacao_matricula', ["todos" => "Todos",
                                    "cursando" => "Cursando",
                                    "aprovado_media" => "Aprovado por Média",
                                    "aprovado_final" => "Aprovado por Final",
                                    "reprovado_media" => "Reprovado por Média",
                                    "reprovado_final" => "Reprovado por Final",
                                    "cancelado" => "Cancelado"
                                ], null, ['class' => 'form-control', 'placeholder' => 'Selecione o status']) !!}
                            </div>
                        </div>
                        <div class="col-md-1">
                            <label for="">&nbsp;</label>
                            <div class="form-group">
                                <input type="submit" id="btnBuscar" class="form-control btn-primary" value="Buscar">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="">&nbsp;</label>
                            <div class="form-group">
                                <input type="submit" formtarget="_blank" id="Buscar" class="form-control btn-primary"
                                       value="Gerar Relatório">
                            </div>
                        </div>

                    </div>
                </form>
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
        <div class="box-body">
        </div>
    </div>

    @if(!is_null($tabela))
        <div class="box box-primary">
            <div class="box-header">
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
            var situacaoSelect = $('#mof_situacao_matricula')
            var btnBuscar = $('#btnBuscar');

            // token
            var token = "{{csrf_token()}}";

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

            // evento do botao Buscar
//            btnBuscar.click(function (event) {
//
//                // parar o evento de submissao do formaulario
//                event.preventDefault();
//
//                var turmaId = turmaSelect.val();
//                var ofertaDisciplinaId = disciplinasOfertadasSelect.val();
//                var situacao = situacaoSelect.val();
//
//                if ((!turmaId || turmaId == '') || (!ofertaDisciplinaId || ofertaDisciplinaId == '')) {
//                    return false;
//                }
//
//                renderTable(turmaId, ofertaDisciplinaId, situacao);
//
//            });

            var renderTable = function (turmaId, ofertaDisciplinaId, situacao) {
                $.harpia.httpget("{{url('/')}}/academico/async/relatoriosmatriculasdisciplina/gettallalunosbysituacao/" + turmaId + "/" + ofertaDisciplinaId + "/" + situacao).done(function (response) {
                    if (!$.isEmptyObject(response)) {
                        var html = '<div class="row"><div class="col-md-12">';

                        var cursandos = new Array();
                        var aprovados = new Array();
                        var reprovados = new Array();
                        var cancelados = new Array();

                        $.each(response, function (key, obj) {
                            if (obj.mof_situacao_matricula == 'reprovado_media' || obj.mof_situacao_matricula == 'reprovado_final') {
                                reprovados.push(obj);
                            } else if (obj.mof_situacao_matricula == 'cursando') {
                                cursandos.push(obj);
                            } else if (obj.mof_situacao_matricula == 'cancelado') {
                                cancelados.push(obj);
                            } else if (['aprovado_media', 'aprovado_final'].indexOf(obj.mof_situacao_matricula) > -1) {
                                aprovados.push(obj);
                            }
                        });

                        // criando a estrutura das tabs
                        var tabs = '<div class="nav-tabs-custom">';
                        tabs += '<ul class="nav nav-tabs">';
                        tabs += '<li class="active">' +
                            '<a href="#tab_1" data-toggle="tab">' +
                            'Cursando ' +
                            '<span data-toggle="tooltip" class="badge bg-blue">' + cursandos.length + '</span>' +
                            '</a></li>';
                        tabs += '<li>' +
                            '<a href="#tab_2" data-toggle="tab">' +
                            'Aprovados <span data-toggle="tooltip" class="badge bg-blue">' + aprovados.length + '</span>' +
                            '</a></li>';
                        tabs += '<li>' +
                            '<a href="#tab_3" data-toggle="tab">' +
                            'Reprovados <span data-toggle="tooltip" class="badge bg-blue">' + reprovados.length + '</span>' +
                            '</a></li>';
                        tabs += '<li>' +
                            '<a href="#tab_4" data-toggle="tab">' +
                            'Cancelados <span data-toggle="tooltip" class="badge bg-blue">' + cancelados.length + '</span>' +
                            '</a></li>';
                        tabs += '</ul>';
                        tabs += '<div class="tab-content">';


                        // Criacao da Tab de Alunos cursando a disciplina
                        var tab1 = '<div class="tab-pane active" id="tab_1">';

                        if (!$.isEmptyObject(cursandos)) {
                            var div = '<div class="row"><div class="col-md-12">';
                            var table1 = '<table class="table table-bordered table-striped">';

                            // cabeçalho da tabela
                            table1 += '<tr>';
                            table1 += '<th>Nome</th>';
                            table1 += '<th width="20%">Situacao de Matricula</th>';
                            table1 += '</tr>';

                            // corpo da tabela
                            $.each(cursandos, function (key, obj) {
                                table1 += '<tr>';
                                table1 += '<td>' + obj.pes_nome + '</td>';
                                table1 += '<td><span class="label label-info">Cursando</span></td>';
                                table1 += '</tr>';

                                table1 += '</tr>';
                            });

                            table1 += '</table>';
                            div += table1;
                            div += '</div></div>';

//                            // criacao do botao de matricular alunos
//                            div += '<div class="row"><div class="col-md-12">';
//                            div += '<button type="submit" formtarget="_blank" class="btn btn-success btnMatricular">Imprimir</button>';
//                            div += '</div></div>';

                            tab1 += div;
                        } else {
                            tab1 += '<p>Sem alunos cursando a disciplina</p>';
                        }

                        tab1 += '</div>';

                        // Criacao da Tab de Alunos aprovados na disciplina
                        var tab2 = '<div class="tab-pane " id="tab_2">';

                        if (!$.isEmptyObject(aprovados)) {
                            var table2 = '<table class="table table-bordered table-striped">';

                            // cabeçalho da tabela
                            table2 += '<tr>';
                            table2 += '<th>Nome</th>';
                            table2 += '<th width="20%">Situacao de Matricula</th>';
                            table2 += '</tr>';

                            // corpo da tabela
                            $.each(aprovados, function (key, obj) {
                                table2 += '<tr>';
                                table2 += '<td>' + obj.pes_nome + '</td>';
                                if (obj.mof_situacao_matricula == 'aprovado_media') {
                                    table2 += '<td><span class="label label-success">Aprovado por Média</span></td>';
                                } else {
                                    table2 += '<td><span class="label label-success">Aprovado por Final</span></td>';
                                }

                                table2 += '</tr>';
                            });

                            table2 += '</table>';
                            tab2 += table2;
                        } else {
                            tab2 += '<p>Sem alunos aprovados na disciplina</p>';
                        }

                        tab2 += '</div>';

                        // Criacao da Tab de Alunos reprovado na disciplina
                        var tab3 = '<div class="tab-pane " id="tab_3">';

                        if (!$.isEmptyObject(reprovados)) {
                            var table3 = '<table class="table table-bordered table-striped">';

                            // cabeçalho da tabela
                            table3 += '<tr>';
                            table3 += '<th>Nome</th>';
                            table3 += '<th width="20%">Situacao de Matricula</th>';
                            table3 += '</tr>';

                            // corpo da tabela
                            $.each(reprovados, function (key, obj) {
                                table3 += '<tr>';
                                table3 += '<td>' + obj.pes_nome + '</td>';
                                if (obj.mof_situacao_matricula == 'reprovado_media') {
                                    table3 += '<td><span class="label label-warning">Reprovado por Média</span></td>';
                                } else {
                                    table3 += '<td><span class="label label-warning">Reprovado por Final</span></td>';
                                }

                                table3 += '</tr>';
                            });

                            table3 += '</table>';
                            tab3 += table3;
                        } else {
                            tab3 += '<p>Sem alunos reprovados na disciplina</p>';
                        }

                        tab3 += '</div>';

                        // Criacao da Tab de Alunos cancelados na disciplina
                        var tab4 = '<div class="tab-pane " id="tab_4">';

                        if (!$.isEmptyObject(cancelados)) {
                            var table4 = '<table class="table table-bordered table-striped">';

                            // cabeçalho da tabela
                            table4 += '<tr>';
                            table4 += '<th>Nome</th>';
                            table4 += '<th width="20%">Situacao de Matricula</th>';
                            table4 += '</tr>';

                            // corpo da tabela
                            $.each(cancelados, function (key, obj) {
                                table4 += '<tr>';
                                table4 += '<td>' + obj.pes_nome + '</td>';
                                table4 += '<td><span class="label label-warning">Cancelado</span></td>';
                                table4 += '</tr>';
                            });

                            table4 += '</table>';
                            tab4 += table4;
                        } else {
                            tab4 += '<p>Sem alunos cancelados na disciplina</p>';
                        }

                        tab4 += '</div>';

                        tabs += tab1;
                        tabs += tab2;
                        tabs += tab3;
                        tabs += tab4;

                        tabs += '</div></div>';

                        html += tabs;
                        html += '</div></div>';

                        $('#boxAlunos').removeClass('hidden');
                        $('#boxAlunos .box-body').empty().append(html);

                    } else {
                        $('#boxAlunos').removeClass('hidden');
                        $('#boxAlunos .box-body').empty().append('<p>Sem alunos matriculados na turma</p>');
                    }
                });
            };


            var hiddenButton = function () {
                var checkboxes = $('#boxAlunos table td input[type="checkbox"]');

                if (checkboxes.is(':checked')) {
                    $(document).find('.btnMatricular').removeClass('hidden');
                } else {
                    $(document).find('.btnMatricular').addClass('hidden');
                }
            };

            $(document).on('click', '#boxAlunos table input[type="checkbox"]', hiddenButton);

            $(document).on('click', '.btnMatricular', function () {

//                event.preventDefault();

                var turmaId = turmaSelect.val();
                var ofertaDisciplinaId = disciplinasOfertadasSelect.val();
                var situacao = situacaoSelect.val();

                if ((!turmaId || turmaId == '') || (!ofertaDisciplinaId || ofertaDisciplinaId == '')) {
                    return false;
                }

                sendMatriculas(turmaId, ofertaDisciplinaId, situacao);
            });

            var sendMatriculas = function (turmaId, ofertaDisciplinaId, situacao) {

                var dados = {
                    trm_id: turmaId,
                    ofd_id: ofertaDisciplinaId,
                    mof_situacao_matricula: situacao,
                    _token: token
                };

                $.harpia.showloading();

                $.ajax({
                    type: 'POST',
                    url: '/academico/async/relatoriosmatriculasdisciplina/postgerarrelatorio',
                    data: dados,
                    success: function () {
                        $.harpia.hideloading();
                        toastr.success('Relatório gerado com sucesso!', null, {progressBar: true});
                    },
                    error: function (xhr, textStatus, error) {
                        $.harpia.hideloading();

                        switch (xhr.status) {
                            case 400:
                                toastr.error(xhr.responseText.replace(/\"/g, ''), null, {progressBar: true});
                                break;
                            default:
                                toastr.error(xhr.responseText.replace(/\"/g, ''), null, {progressBar: true});
                        }
                    }
                });
            };
        });
    </script>
@stop
