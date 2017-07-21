@extends('layouts.modulos.academico')

@section('stylesheets')
    <link rel="stylesheet" href="{{url('/')}}/css/plugins/select2.css">
@stop

@section('title')
    Conclusão de Curso
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-filter"></i> Filtrar Dados
            </h3>
            <!-- /.box-title -->
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <form method="GET" action="#">
                    <div class="col-md-3">
                        {!! Form::label('crs_id', 'Curso*') !!}
                        <div class="form-group">
                            {!! Form::select('crs_id', $cursos, '', ['class' => 'form-control', 'placeholder' => 'Escolha o curso']) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        {!! Form::label('ofc_id', 'Oferta de Curso*') !!}
                        <div class="form-group">
                            {!! Form::select('ofc_id', [], '', ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        {!! Form::label('trm_id', 'Turma*') !!}
                        <div class="form-group">
                            {!! Form::select('trm_id', [], '', ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        {!! Form::label('pol_id', 'Polo*') !!}
                        <div class="form-group">
                            {!! Form::select('pol_id', [], '', ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        {!! Form::label('btn', '&nbsp;') !!}
                        <div class="form-group">
                            <input type="submit" id="btnBuscar" class="form-control btn-primary" value="Buscar">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box-primary -->
    <div class="box box-primary hidden" id="boxAlunos">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-filter"></i> Lista de Alunos
            </h3>
            <!-- /.box-title -->
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body"></div>
        <!-- /.box-body -->
    </div>
@stop

@section('scripts')
    <script src="{{url('/')}}/js/plugins/select2.js"></script>

    <script>
        $(function () {
            $('select').select2();

            var token = "{{csrf_token()}}";

            var cursosSelect = $('#crs_id');
            var ofertasCursoSelect = $('#ofc_id');
            var turmaSelect = $('#trm_id');
            var polosSelect = $('#pol_id');

            // evento change do select de cursos
            cursosSelect.change(function () {
                // limpando selects
                ofertasCursoSelect.empty();
                turmaSelect.empty();
                polosSelect.empty();

                var cursoId = $(this).val();

                if(!cursoId) {
                    return false;
                }

                // faz a consulta pra trazer todas as ofertas de curso
                $.harpia.httpget('{{url("/")}}/academico/async/ofertascursos/findallbycurso/' + cursoId).done(function (response) {
                     if(!$.isEmptyObject(response)) {
                         ofertasCursoSelect.append("<option value=''>Selecione uma oferta</option>");

                         $.each(response, function (key, obj) {
                             ofertasCursoSelect.append("<option value='"+obj.ofc_id+"'>"+obj.ofc_ano+" ("+obj.mdl_nome+")</option>");
                         });
                     } else {
                         ofertasCursoSelect.append("<option value=''>Sem ofertas cadastradas</option>");
                     }
                });
            });

            // evento change do select de ofertas de curso
            ofertasCursoSelect.change(function () {
                // limpando selects
                turmaSelect.empty();
                polosSelect.empty();

                // faz a consulta pra trazer todas as turmas da oferta de curso escolhida
                var ofertaCursoId = $(this).val();

                if(!ofertaCursoId) {
                    return false;
                }

                // buscar turmas
                $.harpia.httpget("{{url('/')}}/academico/async/turmas/findallbyofertacurso/" + ofertaCursoId).done(function (response) {
                    if(!$.isEmptyObject(response)) {
                        turmaSelect.append("<option value=''>Selecione uma turma</option>");

                        $.each(response, function (key, obj) {
                            turmaSelect.append("<option value='"+obj.trm_id+"'>"+obj.trm_nome+"</option>");
                        });
                    } else {
                        turmaSelect.append("<option value=''>Sem turmas cadastradas</option>");
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

            // evento para selecionar todos os checkboxes
            $(document).on('click', '#select_all',function(event) {
                if(this.checked) {
                    $(':checkbox').each(function() {
                        this.checked = true;
                    });
                }
                else {
                    $(':checkbox').each(function() {
                        this.checked = false;
                    });
                }
            });

            // evento click do botao de buscar
            $('#btnBuscar').click(function (event) {
                event.preventDefault();

                var ofertaCursoId = $('#ofc_id').val();
                var turmaId = $('#trm_id').val();
                var poloId = $('#pol_id').val();

                if(!ofertaCursoId || !turmaId || !poloId) {
                    return false;
                }

                renderTableAlunos(ofertaCursoId, turmaId, poloId);
            });

            // evento do botão de confirmar a matricula na(s) disciplina(s)
            $(document).on('click', '.confirmConclusao', function (e) {

                e.preventDefault();

                var quant = $('.matriculas:checked').length;

                if(!(quant > 0)) {
                    return false;
                }

                var matriculasIds = new Array();
                var ofertaCursoId = ofertasCursoSelect.val();

                $('.matriculas:checked').each(function () {
                    matriculasIds.push($(this).val());
                });

                sendMatriculas(ofertaCursoId, matriculasIds);
            });

            var renderTableAlunos = function (ofertaCursoId, turmaId, poloId) {

                var data = 'ofc_id=' + ofertaCursoId + '&trm_id=' + turmaId + '&pol_id=' + poloId;

                $.harpia.httpget("{{url('/')}}/academico/async/conclusaocurso/findallalunosaptosounao?" + data).done(function (response) {

                    $('#boxAlunos').removeClass('hidden');

                    var boxAlunos = $('#boxAlunos .box-body');
                    boxAlunos.empty();

                    if(!$.isEmptyObject(response)) {
                        var table = '<div class="row">';
                        table += '<div class="col-md-12">';
                        table += '<table class="table table-bordered table-hover">';

                        table += '<tr>';
                        table += '<th width="1%"><label><input id="select_all" type="checkbox"></label></th>';
                        table += '<th width="1%">#</th>';
                        table += '<th>Aluno</th>';
                        table += '<th width="20%">Situação</th>';
                        table += '</tr>';

                        $.each(response, function (key, obj) {
                            table += '<tr>';
                            if(obj.status['status'] == 'success') {
                                table += '<td><label><input type="checkbox" class="matriculas" value="'+obj.mat_id+'"></label></td>';
                            } else {
                                table += '<td></td>';
                            }
                            table += '<td>'+obj.mat_id+'</td>';
                            table += '<td>'+obj.pes_nome+'</td>';
                            table += '<td><span class="label label-'+obj.status['status']+'">'+obj.status['message']+'</span>';
                            if (obj.status['status'] == 'info') {
                                table += '<p><strong>Data de Conclusão:</strong> '+obj.status['data_conclusao']+'</p>';
                            }
                            table += '</td>';
                            table += '</tr>';
                        });

                        table += '</table></div></div>';

                        table += "<div class='row'>";
                        table += "<div class='col-md-12'>";
                        table += "<div class='form-group'>";
                        table += "<button class='btn btn-primary confirmConclusao hidden'>Confirmar Conclusão</button>";
                        table += "</div></div></div>";

                        boxAlunos.append(table);
                        hiddenButton();
                    } else {
                        boxAlunos.append('<p>Sem registros para apresentar</p>');
                    }
                });
            };

            var hiddenButton = function() {
                var checkboxes = $('#boxAlunos table td input[type="checkbox"]');

                if(checkboxes.is(':checked')){
                    $(document).find('.confirmConclusao').removeClass('hidden');
                }else{
                    $(document).find('.confirmConclusao').addClass('hidden');
                }
            };

            $(document).on('click', '#boxAlunos table input[type="checkbox"]', hiddenButton);

            var sendMatriculas = function (ofertaCursoId, matriculasIds) {

                var dados = {
                    matriculas: matriculasIds,
                    ofc_id: ofertaCursoId,
                    _token: token
                };

                $.harpia.showloading();

                $.ajax({
                    type: 'POST',
                    url: '/academico/async/conclusaocurso/concluirmatriculas',
                    data: dados,
                    success: function(response) {

                        $.harpia.hideloading();

                        toastr.success('Conclusão de Curso efetuada com sucesso!', null, {progressBar: true});

                        var ofertaCursoId = $('#ofc_id').val();
                        var turmaId = $('#trm_id').val();
                        var poloId = $('#pol_id').val();

                        renderTableAlunos(ofertaCursoId, turmaId, poloId);
                    },
                    error: function(xhr, textStatus, error) {
                        $.harpia.hideloading();

                        switch (xhr.status) {
                            case 400:
                                toastr.error(xhr.responseText, null, {progressBar: true});
                                break;
                            default:
                                toastr.error(xhr.responseText, null, {progressBar: true});
                        }
                    }
                });
            };
        });
    </script>
@stop