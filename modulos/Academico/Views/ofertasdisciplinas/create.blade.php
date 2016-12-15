@extends('layouts.modulos.academico')

@section('stylesheets')
  <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection

@section('title')
    Ofertas de Disciplinas
@stop

@section('subtitle')
    Cadastro de oferta de disciplina
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
                    {!! Form::label('ofc_id', 'Oferta do Curso*', ['class' => 'control-label']) !!}
                    {!! Form::select('ofc_id', [], null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group col-md-3">
                    {!! Form::label('ofd_trm_id', 'Turma*', ['class' => 'control-label']) !!}
                    {!! Form::select('ofd_trm_id', [], null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group col-md-2">
                    {!! Form::label('ofd_per_id', 'Período Letivo*', ['class' => 'control-label']) !!}
                    {!! Form::select('ofd_per_id', [], null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group col-md-1">
                    <label for="" class="control-label"></label>
                    <button class="btn btn-primary form-control" id="btnLocalizar"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>

    <div class="box box-primary" id="formDisciplinas">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de ofertas de disciplinas</h3>
        </div>
        <div class="box-body">
            @include('Academico::ofertasdisciplinas.includes.formulario_create')
        </div>
    </div>

    <div class="box box-primary hidden" id="boxDisciplinas">
        <div class="box-header with-border">
            <h3 class="box-title">Disciplinas Ofertadas</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <div class="col-md-12 conteudo"></div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
@stop

@section('scripts')
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function() {
                $("select").select2();
                var token = "{{csrf_token()}}";

                var selectTurmas = $('#ofd_trm_id');
                var selectPeriodos = $('#ofd_per_id');
                var selectProfessores = $('#ofd_prf_id');
                var selectDisciplinas = $('#ofd_mdc_id');

                var boxDisciplinas = $('#boxDisciplinas');
                var boxFormDisciplinas = $('#formDisciplinas');
                boxFormDisciplinas.hide();


                // Botao de Localizar Disciplinas Ofertadas
                $('#btnLocalizar').click(function () {
                    var turma = selectTurmas.val();
                    var periodo = $('#ofd_per_id').val();

                    if(turma == '' || periodo == '') {
                        return false;
                    }

                    localizarDisciplinasOfertadas(turma, periodo);
                });

                $('#btnAdicionar').click(function (e) {
                    e.preventDefault();

                    var turmaId = selectTurmas.val();
                    var periodoId = selectPeriodos.val();
                    var professorId = selectProfessores.val();
                    var disciplinaId = selectDisciplinas.val();
                    var qtdVagas = $('#ofd_qtd_vagas').val();

                    if(turmaId == '' || periodoId == '' || professorId == '' || disciplinaId == '' || qtdVagas == '') {
                        return false;
                    }

                    var token = "{{csrf_token()}}";

                    var dados = {
                        ofd_trm_id: turmaId,
                        ofd_per_id: periodoId,
                        ofd_prf_id: professorId,
                        ofd_mdc_id: disciplinaId,
                        ofd_qtd_vagas: qtdVagas,
                        _token: token
                    };

                    $.harpia.showloading();
                    var result = false;

                    $.ajax({
                        url: "{{url('/')}}/academico/async/ofertasdisciplinas/oferecerdisciplina",
                        data: dados,
                        method: 'POST',
                        success: function (data) {
                            $.harpia.hideloading();
                            toastr.success('Disciplina ofertada com sucesso!', null, {progressBar: true});
                            localizarDisciplinasOfertadas(dados.ofd_trm_id, dados.ofd_per_id);
                        },
                        error: function (xhr, textStatus, error) {
                            $.harpia.hideloading();

                            switch (xhr.status) {
                                case 400:
                                    toastr.error(xhr.responseText, null, {progressBar: true});
                                    break;
                                default:
                                    toastr.error(xhr.responseText, null, {progressBar: true});

                                    result = false;
                            }
                        }
                    });

                    resetForm();
                });

                $(document).on('click', '.btn-delete', function (event) {
                    event.preventDefault();

                    var button = $(this);

                    swal({
                        title: "Tem certeza que deseja excluir?",
                        text: "Você não poderá recuperar essa informação!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sim, pode excluir!",
                        cancelButtonText: "Não, quero cancelar!",
                        closeOnConfirm: true
                    }, function(isConfirm){
                        if (isConfirm) {

                            var ofd_id = button.closest('form').find('input[name="id"]').val();
                            var turmaId = $('#ofd_trm_id').val();
                            var periodoId = $('#ofd_per_id').val();

                            var data = {ofd_id : ofd_id, _token : token};

                            $.harpia.showloading();

                            var result = false;

                            $.ajax({
                                type: 'POST',
                                url: '/academico/async/ofertasdisciplinas/deletarofertadisciplina',
                                data: data,
                                success: function (data) {
                                    $.harpia.hideloading();

                                    toastr.success('Oferta excluída com sucesso!', null, {progressBar: true});
                                    localizarDisciplinasOfertadas(turmaId, periodoId);
                                    result = resp;
                                },
                                error: function (xhr, textStatus, error) {
                                    $.harpia.hideloading();

                                    switch (xhr.status) {
                                        case 400:
                                            toastr.error('Erro ao tentar deletar a oferta de disciplina.', null, {progressBar: true});
                                            break;
                                        default:
                                            toastr.error(xhr.responseText, null, {progressBar: true});

                                            result = false;
                                    }
                                }
                            });
                        }
                    });
                });

                var localizarDisciplinasOfertadas = function (turmaId, periodoId) {
                    $.harpia.httpget("{{url('/')}}/academico/async/ofertasdisciplinas/findall?ofd_trm_id=" + turmaId + "&ofd_per_id=" + periodoId)
                            .done(function (data) {
                                console.log(data);
                                boxDisciplinas.removeClass('hidden');
                                boxFormDisciplinas.show();

                                boxDisciplinas.find('.conteudo').empty();
                                if(!$.isEmptyObject(data)) {

                                    var table = '';
                                    table += "<table class='table table-bordered'>";
                                    table += '<tr>';
                                    table += "<th>Disciplina</th>";
                                    table += "<th>Carga Horária</th>";
                                    table += "<th>Créditos</th>";
                                    table += "<th>Vagas</th>";
                                    table += "<th>Professor</th>";
                                    table += "<th>Ações</th>";
                                    table += '</tr>';

                                    $.each(data, function (key, obj) {
                                        table += '<tr>';
                                        table += "<td>"+obj.dis_nome+"</td>";
                                        table += "<td>"+obj.dis_carga_horaria+"</td>";
                                        table += "<td>"+obj.dis_creditos+"</td>";
                                        table += "<td>"+obj.qtdMatriculas+"/<strong>"+obj.ofd_qtd_vagas+"</strong></td>";
                                        table += "<td>"+obj.pes_nome+"</td>";
                                        if(obj.qtdMatriculas == 0) {
                                            table += '<td>' +
                                                    '<form action="" method="POST">' +
                                                    '<input type="hidden" name="id" value="'+obj.ofd_id+'">' +
                                                    '<input type="hidden" name="_token" value="'+token+'">' +
                                                    '<input type="hidden" name="_method" value="POST">' +
                                                    '<button class="btn-delete btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>' +
                                                    '</form></td>';
                                        } else {
                                            table += '<td></td>';
                                        }
                                        table += '</tr>';
                                    });

                                    table += "</table>";
                                    boxDisciplinas.find('.conteudo').append(table);
                                } else {
                                    boxDisciplinas.find('.conteudo').append('<p>O periodo letivo não possui disciplinas ofertadas</p>');
                                }
                            });
                };

                var resetForm = function () {
                    resetSelectMatrizes();
                    $('#ofd_mdo_id').empty();
                    $('#ofd_mdc_id').empty();
                    resetSelectProfessores();
                    $('#ofd_qtd_vagas').val('');
                };

                var resetSelectMatrizes = function () {
                    $('#mtc_id').empty();
                    var crsId = $('#crs_id').val();
                    // Populando o select de matrizes
                    $.harpia.httpget("{{url('/')}}/academico/async/matrizescurriculares/findallbycurso/" + crsId)
                            .done(function (data) {
                                if(!$.isEmptyObject(data)) {
                                    $('#mtc_id').append("<option value=''>Selecione a matriz</option>");
                                    $.each(data, function (key, value) {
                                        $('#mtc_id').append('<option value="'+value.mtc_id+'">'+value.mtc_titulo+'</option>');
                                    });
                                } else {
                                    $('#mtc_id').append("<option value=''>Sem matrizes cadastradas</option>");
                                }
                            });
                };
                
                var resetSelectProfessores = function () {
                    $('#ofd_prf_id').empty();

                    $.harpia.httpget("{{url('/')}}/academico/async/professores/findall")
                            .done(function (data) {

                                if(!$.isEmptyObject(data)) {
                                    $('#ofd_prf_id').append("<option value=''>Selecione o professor</option>");
                                    $.each(data, function (key, value) {
                                        $('#ofd_prf_id').append('<option value="'+key+'">'+value+'</option>');
                                    });
                                } else {
                                    $('#ofd_prf_id').append("<option value=''>Sem professores cadastrados</option>");
                                }
                            });
                };
            });
    </script>
@endsection
