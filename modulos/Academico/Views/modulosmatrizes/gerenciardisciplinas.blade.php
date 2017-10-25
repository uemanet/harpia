@extends('layouts.modulos.academico')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection

@section('title')
    Gerenciamento de Disciplinas
@stop

@section('subtitle')
    Gerenciamento de disciplinas :: {{$curso->crs_nome}} :: {{$matriz->mtc_titulo}} :: {{ $modulo->mdo_nome }}
@stop

@section('content')
    <!-- Box Buscar Disciplinas -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">
                Buscar Disciplinas
            </h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <form action="#" id="formLocalizar">
                    <div class="col-md-9">
                        <div class="form-group">
                            <input type="text" class="form-control" id="disciplina" placeholder="Nome da Disciplina">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="form-control btn btn-primary" id="btnLocalizar">Buscar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Box Disciplinas Localizadas -->
    <div id="boxDisciplinasLocalizadas" class="box box-primary hidden">
        <div class="box-header with-border">
            <h3 class="box-title">
                Disciplinas Localizadas
            </h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
        </div>
    </div>

    <!-- Box Disciplinas Cadastradas no Módulo -->
    <div id="boxDisciplinasCadastradas" class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">
                Disciplinas do módulo
            </h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            @if($disciplinas->count())
                <table class="table table-bordered table-hover" id="tableDisciplinasModulo">
                    <thead>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Nível</th>
                    <th>Carga Horária</th>
                    <th>Créditos</th>
                    <th>Tipo da Disciplina</th>
                    <th>Pré-Requisitos</th>
                    <th>Ações</th>
                    </thead>
                    <tbody>
                    @foreach($disciplinas as $disciplina)
                        <tr>
                            <td>{{$disciplina->mdc_id}}</td>
                            <td>{{$disciplina->dis_nome}}</td>
                            <td>{{$disciplina->nvc_nome}}</td>
                            <td>{{$disciplina->dis_carga_horaria}} horas</td>
                            <td>{{$disciplina->dis_creditos}}</td>
                            <td>{{$disciplina->mdc_tipo_disciplina}}</td>
                            @if(!empty($disciplina->pre_requisitos))
                                <td>
                                    @foreach($disciplina->pre_requisitos as $disc)
                                        <p>{{ $disc->dis_nome }}</p>
                                    @endforeach
                                </td>
                            @else
                                <td>Sem pré-requisitos</td>
                            @endif
                            <td>
                                {!!
                                    ActionButton::grid([
                                        'type' => 'SELECT',
                                        'config' => [
                                            'classButton' => 'btn-default',
                                            'label' => 'Selecione'
                                        ],
                                        'buttons' => [
                                            [
                                                'classButton' => 'btnEdit',
                                                'icon' => 'fa fa-pencil',
                                                'route' => 'academico.cursos.matrizescurriculares.modulosmatrizes.editardisciplinas',
                                                'parameters' => ['id' => $disciplina->mdc_id],
                                                'label' => 'Editar',
                                                'id' => $disciplina->mdc_id,
                                                'method' => 'get'
                                            ],
                                            [
                                                'classButton' => 'btn btn-delete',
                                                'icon' => 'fa fa-trash',
                                                'route' => 'academico.cursos.matrizescurriculares.modulosmatrizes.delete',
                                                'id' => $modulo->mdo_id,
                                                'label' => 'Excluir',
                                                'method' => 'post'
                                            ]
                                        ]
                                    ])
                                !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <p>Sem disciplinas cadastradas</p>
            @endif
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

    <script type="text/javascript">

        $(function () {

            var matriz = "{{$matriz->mtc_id}}";
            var modulo = "{{$modulo->mdo_id}}";
            var csrf_token = "{{csrf_token()}}";

            /* Pega o evento do botao de localizar disciplinas,e faz a busca via async */
            $('#btnLocalizar').click(function (event) {
                event.preventDefault();

                var disciplina = $('#disciplina').val();

                if (!disciplina) {
                    return false;
                }

                // função que renderiza a tabela de disciplinas localizadas
                renderTableLocalizadas(disciplina, matriz, modulo);
            });

            /* Pega o evento do botão de adicionar disciplina, e faz a adição via ajax */
            $(document).on('click', '.btn-add-disciplina', function (e) {

                e.currentTarget.setAttribute("disabled", true);

                var linha = $(this).closest('tr');

                // função que adiciona uma disciplina no módulo
                addDisciplina(linha);

                verifyTablesEmpty();
            });

            //Pega o evento do clique do botao delete e faz o tratamento via ajax.
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
                }, function (isConfirm) {
                    if (isConfirm) {

                        var mdc_id = button.closest('form').find('input[name="id"]').val();
                        var linha = button.closest('tr');

                        var data = {mdc_id: mdc_id, mtc_id: matriz, _token: csrf_token};

                        $.harpia.showloading();

                        var result = false;

                        $.ajax({
                            type: 'POST',
                            url: '/academico/async/modulosdisciplinas/deletardisciplina',
                            data: data,
                            success: function (data) {
                                $.harpia.hideloading();

                                toastr.success('Disciplina excluída com sucesso!', null, {progressBar: true});
                                renderTableDisciplinasModulo(modulo);
                            },
                            error: function (xhr, textStatus, error) {
                                $.harpia.hideloading();

                                switch (xhr.status) {
                                    case 400:
                                        toastr.error('Erro ao tentar deletar a disciplina.', null, {progressBar: true});
                                        break;
                                    default:
                                        toastr.error(xhr.responseText, null, {progressBar: true});

                                        result = false;
                                }
                            }
                        });

                        verifyTablesEmpty();
                    }
                });

            });

            /* Functions */

            var renderTableLocalizadas = function (disciplinaNome, matrizId, moduloId) {

                $('#boxDisciplinasLocalizadas').removeClass('hidden');

                var boxBody = $('#boxDisciplinasLocalizadas .box-body');

                $.harpia.httpget('{{url('/')}}/academico/async/disciplinas/findbynome/' + matrizId + '/' + disciplinaNome + '/' + moduloId).done(function (data) {

                    boxBody.empty();

                    if (!$.isEmptyObject(data.disciplinas)) {

                        var table = '<table class="table table-bordered table-hover" id="tableDisciplinasLocalizadas">';
                        table += '<thead>';
                        table += '<tr>';

                        if (!$.isEmptyObject(data.prerequisitos)) {
                            table += '<th width="1%">#</th>';
                            table += '<th width="20%">Nome</th>';
                            table += '<th width="5%">Carga Horária</th>';
                            table += '<th width="5%">Créditos</th>';
                            table += '<th width="13%">Tipo da Disciplina</th>';
                            table += '<th>Pré-Requisitos</th>';
                            table += '<th width="10%">Ações</th>';
                        } else {
                            table += '<th width="1%">#</th>';
                            table += '<th>Nome</th>';
                            table += '<th width="10%">Carga Horária</th>';
                            table += '<th width="5%">Créditos</th>';
                            table += '<th width="18%">Tipo da Disciplina</th>';
                            table += '<th width="10%">Ações</th>';
                        }

                        table += '</tr>';
                        table += '</thead>';
                        table += '<tbody>';

                        $.each(data.disciplinas, function (key, value) {
                            table += '<tr>';

                            table += '<td id="dis_id">' + value.dis_id + '</td>';
                            table += '<td id="nome">' + value.dis_nome + '</td>';
                            table += '<td id="cargahoraria">' + value.dis_carga_horaria + ' horas</td>';
                            table += '<td id="creditos">' + value.dis_creditos + '</td>';

                            table += '<td><div class="form-group">';
                            table += '<select class="form-control" id="mdc_tipo_disciplina">';
                            table += '<option value="obrigatoria" selected>OBRIGATÓRIA</option>';
                            table += '<option value="eletiva">ELETIVA</option>';
                            table += '<option value="optativa">OPTATIVA</option>';
                            table += '<option value="tcc">TCC</option>';
                            table += '</select></div></td>';

                            if (!$.isEmptyObject(data.prerequisitos)) {
                                table += '<td><div class="form-group">';
                                table += '<select class="form-control" id="prerequisitos" multiple>';
                                $.each(data.prerequisitos, function (key, obj) {
                                    table += '<option value="' + obj.mdc_id + '">' + obj.dis_nome + '</option>';
                                });
                                table += '</select></div></td>';
                            }

                            table += '<td>';
                            table += '<button class="btn btn-success btn-add-disciplina" type="button"><i class="fa fa-plus"></i> Adicionar ao módulo</button>';
                            table += '</td>';

                            table += '</tr>';

                        });

                        table += '</tbody>';
                        table += '</table>';

                        boxBody.append(table);

                        $(document).find('select').select2();
                    } else {
                        boxBody.append('<p>Sem registros</p>');
                    }
                });
            };

            var addDisciplina = function (linha) {

                var prerequisitos = new Array();

                if (linha.find('#prerequisitos').length) {
                    prerequisitos = linha.find('#prerequisitos').val();
                }

                var data = {
                    dis_id: linha.find('#dis_id').text(),
                    tipo_disciplina: linha.find('#mdc_tipo_disciplina').val(),
                    pre_requisitos: prerequisitos,
                    mtc_id: matriz,
                    mod_id: modulo,
                    _token: csrf_token
                };

                $.harpia.showloading();

                $.ajax({
                    type: 'POST',
                    url: '/academico/async/modulosdisciplinas/adicionardisciplina',
                    data: data,
                    success: function (response) {
                        $.harpia.hideloading();

                        toastr.success('Disciplina adicionada com sucesso!', null, {progressBar: true});

                        linha.remove();

                        renderTableDisciplinasModulo(modulo);
                    },
                    error: function (xhr, textStatus, error) {
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

            var renderTableDisciplinasModulo = function (moduloId) {
                var boxBody = $('#boxDisciplinasCadastradas .box-body');

                $.harpia.httpget('{{url("/")}}/academico/async/modulosdisciplinas/getalldisciplinasbymodulo/' + moduloId).done(function (response) {
                    boxBody.empty();

                    if (!$.isEmptyObject(response)) {
                        var table = '<table class="table table-bordered table-hover" id="tableDisciplinasCadastradas">';

                        // cabeçalho da tabela
                        table += '<thead>';
                        table += '<tr>';
                        table += '<th>#</th>';
                        table += '<th>Nome</th>';
                        table += '<th>Nível</th>';
                        table += '<th>Carga Horária</th>';
                        table += '<th>Créditos</th>';
                        table += '<th>Tipo da Disciplina</th>';
                        table += '<th>Pré-Requisitos</th>';
                        table += '<th>Ações</th>';
                        table += '</tr>';
                        table += '</thead>';

                        table += '<tbody>';
                         $.each(response, function (key, obj) {
                            table += '<tr>';
                            table += '<td>' + obj.mdc_id + '</td>';
                            table += '<td>' + obj.dis_nome + '</td>';
                            table += '<td>' + obj.nvc_nome + '</td>';
                            table += '<td>' + obj.dis_carga_horaria + ' horas</td>';
                            table += '<td>' + obj.dis_creditos + '</td>';
                            table += '<td>' + obj.mdc_tipo_disciplina + '</td>';
                            var prerequisitos = obj.pre_requisitos;
                            if (prerequisitos.length) {
                                table += '<td>';

                                $.each(prerequisitos, function (key, value) {
                                    table += '<p>' + value.dis_nome + '</p>';
                                });

                                table += '</td>';
                            } else {
                                table += '<td>Sem pré-requisitos</td>';
                            }
                            table += '<td>';
                            table += '<form action="">';
                            table += '<input type="hidden" name="id" value="' + obj.mdc_id + '">';
                            table += '<input type="hidden" name="mtc_id" value="' + matriz + '">';
                            table += '<input type="hidden" name="_token" value="' + csrf_token + '">';
                            table += '<input type="hidden" name="_method" value="POST">';
                            table += '<button class="btn-delete btn btn-danger btn-sm"><i class="fa fa-trash"></i> Excluir</button>';
                            table += '</form></td>';
                        });
                        table += '</tbody>';

                        boxBody.append(table);
                    } else {
                        boxBody.append('<p>Sem disciplinas cadastradas</p>');
                    }
                });
            };

            var verifyTablesEmpty = function () {

                var linhas = $('#tableDisciplinasLocalizadas tbody tr').length;

                if (!((linhas - 1) > 0)) {
                    $('#boxDisciplinasLocalizadas .box-body').empty();
                    $('#boxDisciplinasLocalizadas .box-body').append('<p>Sem registros</p>');
                }
            };
        });
    </script>
@stop