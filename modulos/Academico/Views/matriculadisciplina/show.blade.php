@extends('layouts.modulos.academico')

@section('title', 'Matricular Aluno na Disciplina')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
    <link rel="stylesheet" href="{{asset('/css/plugins/icheck/minimal/icheck.css')}}">
@endsection

@section('content')

    @include('Geral::pessoas.includes.dadospessoais')

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
                    <select id="crs_id" class="form-control">
                        @if($matriculas->count())
                            <option>Selecione o curso</option>
                            @foreach($matriculas as $matricula)
                                <option value="{{$matricula->crs_id}}" data-trm-id={{$matricula->trm_id}} data-mat-id={{$matricula->mat_id}}>{{$matricula->crs_nome}}</option>
                            @endforeach
                        @else
                            <option value="">Nenhuma matrícula disponível</option>
                        @endif
                    </select>
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

    <div class="box box-primary hidden" id="boxDisciplinasNaoMatriculadas">
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

    <div class="box box-primary hidden" id="boxDisciplinasMatriculadas">
        <div class="box-header with-border">
            <h3 class="box-title">Disciplinas Matriculadas</h3>

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
    <script src="{{asset('/js/plugins/icheck/icheck.min.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("select").select2();

            $('input[type="checkbox"]').iCheck();

            var token = "{{csrf_token()}}";
            var alunoId = "{{$aluno->alu_id}}";

            var boxDisciplinasNaoMatriculadas = $('#boxDisciplinasNaoMatriculadas');
            var boxDisciplinasMatriculadas = $('#boxDisciplinasMatriculadas');
            var boxFormDisciplinas = $('#formDisciplinas');
            boxFormDisciplinas.hide();

            // Botao de Localizar Disciplinas Ofertadas
            $('#btnLocalizar').click(function () {
                var turma = $('#crs_id option:selected').attr('data-trm-id');
                var periodo = $('#ofd_per_id').val();

                if(turma == '' || periodo == '') {
                    return false;
                }

                localizarDisciplinasOfertadas(turma, periodo, alunoId);
            });

            $('#crs_id').change(function () {
                var turmaId = $(this).find('option:selected').attr('data-trm-id');
                var selectPeriodos = $('#ofd_per_id');

                if(turmaId) {
                    $.harpia.httpget("{{url('/')}}/academico/async/periodosletivos/findallbyturma/"+turmaId)
                    .done(function (response) {
                        selectPeriodos.empty();
                        if(!$.isEmptyObject(response))
                        {
                            selectPeriodos.append("<option value=''>Selecione um periodo</option>");
                            $.each(response, function (key, obj) {
                               selectPeriodos.append("<option value='"+obj.per_id+"'>"+obj.per_nome+"</option>");
                            });
                        } else {
                            selectPeriodos.append("<option value=''>Sem períodos disponíveis</option>");
                        }
                    });
                }
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

            // evento do botão de confirmar a matricula na(s) disciplina(s)
            $(document).on('click', '#confirmMatricula', function (e) {

                var quant = $('.ofertas:checked').length;

                if(!(quant > 0)) {
                    return false;
                }

                var ofertasIds = new Array();
                var matriculaId = $('#crs_id option:selected').attr('data-mat-id');

                $('.ofertas:checked').each(function () {
                    ofertasIds.push($(this).val());
                });

                sendDisciplinas(matriculaId,ofertasIds);
            });

            var localizarDisciplinasOfertadas = function (turmaId, periodoId, alunoId) {
                $.harpia.httpget("{{url('/')}}/academico/async/matriculasofertasdisciplinas/findalldisciplinasbyalunoturmaperiodo/"+alunoId+"/"+turmaId+"/"+periodoId)
                        .done(function (data) {
                            boxDisciplinasNaoMatriculadas.removeClass('hidden');
                            boxDisciplinasMatriculadas.removeClass('hidden');
                            boxFormDisciplinas.show();

                            boxDisciplinasNaoMatriculadas.find('.conteudo').empty();
                            boxDisciplinasMatriculadas.find('.conteudo').empty();

                            var disciplinasOfertadas = new Array();
                            var disciplinasCursadas = new Array();

                            if(!$.isEmptyObject(data)) {
                                $.each(data, function (key, obj) {
                                   if(obj.matriculado) {
                                       disciplinasCursadas.push(obj);
                                   } else {
                                       disciplinasOfertadas.push(obj);
                                   }
                                });
                            }

                            renderTableDisciplinasNaoMatriculadas(disciplinasOfertadas, disciplinasCursadas.length);
                            renderTableDisciplinasMatriculadas(disciplinasCursadas);
                        });
            };

            var renderTableDisciplinasNaoMatriculadas = function (disciplinas, quantDisciplinasCursadas) {

                if(disciplinas.length) {

                    var table = '';
                    table += "<table class='table table-bordered'>";
                    table += '<tr>';
                    table += '<th><label><input class="icheckbox_minimal-blue" id="select_all" type="checkbox"></label></th>';
                    table += "<th>Disciplina</th>";
                    table += "<th>Carga Horária</th>";
                    table += "<th>Créditos</th>";
                    table += "<th>Vagas</th>";
                    table += "<th>Professor</th>";
                    table += "<th>Status</th>";
                    table += '</tr>';

                    $.each(disciplinas, function (key, obj) {
                        table += '<tr>';

                        if(obj.quant_matriculas == obj.ofd_qtd_vagas) {
                            table += "<td></td>";
                        } else {
                            table += "<td><label><input type='checkbox' class='icheckbox_minimal-blue ofertas' value='"+obj.ofd_id+"'></label></td>";
                        }
                        table += "<td>"+obj.dis_nome+"</td>";
                        table += "<td>"+obj.dis_carga_horaria+"</td>";
                        table += "<td>"+obj.dis_creditos+"</td>";
                        table += "<td>"+obj.quant_matriculas+"/"+"<strong>"+obj.ofd_qtd_vagas+"</strong></td>";
                        table += "<td>"+obj.pes_nome+"</td>";
                        if(obj.quant_matriculas == obj.ofd_qtd_vagas) {
                            table += "<td><span class='label label-danger'>Não Disponível</span></td>";
                        } else {
                            table += "<td><span class='label label-success'>Disponível</span></td>";
                        }
                        table += '</tr>';
                    });

                    table += "</table>";

                    var button = '';
                    button += "<div class='form-group'>";
                    button += "<button class='btn btn-primary pull-right' id='confirmMatricula'>Confirmar Matricula</button>";
                    button += "</div>";

                    boxDisciplinasNaoMatriculadas.find('.conteudo').append(table);
                    boxDisciplinasNaoMatriculadas.find('.conteudo').append(button);
                } else {
                    if(quantDisciplinasCursadas > 0) {
                        boxDisciplinasNaoMatriculadas.find('.conteudo').append('<p>Aluno já está matriculado em todas as disciplinas ofertadas para este período</p>');
                    } else {
                        boxDisciplinasNaoMatriculadas.find('.conteudo').append('<p>Não há disponibilidade de disciplinas ofertadas para este período</p>');
                    }
                }
            };

            var renderTableDisciplinasMatriculadas = function (disciplinas) {

                if(disciplinas.length) {

                    var table = '';
                    table += "<table class='table table-bordered'>";
                    table += '<tr>';
                    table += "<th>Disciplina</th>";
                    table += "<th>Carga Horária</th>";
                    table += "<th>Créditos</th>";
                    table += "<th>Vagas</th>";
                    table += "<th>Professor</th>";
                    table += "<th>Status</th>";
                    table += '</tr>';

                    $.each(disciplinas, function (key, obj) {
                        table += '<tr>';
                        table += "<td>"+obj.dis_nome+"</td>";
                        table += "<td>"+obj.dis_carga_horaria+"</td>";
                        table += "<td>"+obj.dis_creditos+"</td>";
                        table += "<td>"+obj.quant_matriculas+"/"+"<strong>"+obj.ofd_qtd_vagas+"</strong></td>";
                        table += "<td>"+obj.pes_nome+"</td>";
                        table += "<td><span class='label label-success'>Matriculado</span></td>";
                        table += '</tr>';
                    });

                    table += "</table>";
                    boxDisciplinasMatriculadas.find('.conteudo').append(table);
                } else {
                    boxDisciplinasMatriculadas.find('.conteudo').append('<p>Aluno não está matriculado em disciplinas deste período</p>');
                }
            };

            var sendDisciplinas = function (matriculaId, ofertasIds) {

                var dados = {
                    ofertas: ofertasIds,
                    mof_mat_id: matriculaId,
                    _token: token
                };

                $.harpia.showloading();

                var result = false;

                $.ajax({
                    type: 'POST',
                    url: '/academico/async/matriculasofertasdisciplinas/matricular',
                    data: dados,
                    success: function (data) {
                        $.harpia.hideloading();

                        toastr.success('Aluno matriculado com sucesso!', null, {progressBar: true});

                        var turma = $('#crs_id option:selected').attr('data-trm-id');
                        var periodo = $('#ofd_per_id').val();

                        localizarDisciplinasOfertadas(turma, periodo, alunoId);
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
            };

        });
    </script>

@endsection