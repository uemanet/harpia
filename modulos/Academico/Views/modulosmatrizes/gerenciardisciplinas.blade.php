@extends('layouts.modulos.academico')

@section('title')
    Módulos
@stop

@section('subtitle')
    Adicionar disciplinas
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
                <div class="col-md-9">
                    <div class="form-group">
                        <input type="text" class="form-control" id="disciplina" placeholder="Nome da Disciplina">
                    </div>
                </div>
                <div class="col-md-3">
                    <button class="form-control btn btn-primary" id="localizar">Buscar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="box box-primary hidden">
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
            <div id="table-localizadas">
                <table class="table table-bordered table-hover" id="localizadas">
                    <thead>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Nível</th>
                    <th>Carga Horária</th>
                    <th>Créditos</th>
                    <th>Tipo de Avaliação</th>
                    <th>Ações</th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div id="mensagem-localizadas" class="hidden">
                <p>Sem registros</p>
            </div>
        </div>
    </div>

    <div class="box box-primary">
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
            <table class="table table-bordered table-hover" id="selecionadas">
                <thead>
                <th>#</th>
                <th>DISID</th>
                <th>Nome</th>
                <th>Nível</th>
                <th>Carga Horária</th>
                <th>Créditos</th>
                <th>Tipo de Avaliação</th>
                <th>Ações</th>
                </thead>
                <tbody>
                @foreach($disciplinas as $disciplina)
                    <tr>
                        <td>{{$disciplina->mdc_id}}</td>
                        <td>{{$disciplina->dis_id}}</td>
                        <td>{{$disciplina->dis_nome}}</td>
                        <td>{{$disciplina->nvc_nome}}</td>
                        <td>{{$disciplina->dis_carga_horaria}} horas</td>
                        <td>{{$disciplina->dis_creditos}}</td>
                        <td>{{ $disciplina->mdc_tipo_avaliacao }}</td>
                        <td>
                            <form action="">
                                <input type="hidden" name="id" value="{{$disciplina->mdc_id}}">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <input type="hidden" name="_method" value="POST">
                                <button class="btn-delete btn btn-danger btn-sm"><i class="fa fa-trash"></i> Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div id="salvar" class="col-md-2 pull-right hidden">
                <button type="submit" id="btnSalvar" class="btn btn-primary btn-block">Salvar</button>
            </div>
            <p id="msg" class="hidden">Sem disciplinas cadastradas</p>
        </div>
    </div>
@stop

@section('scripts')
    <script type="application/javascript">

        var matriz = "{{$matriz}}";
        var modulo = "{{$modulo}}";
        var csrf_token = "{{csrf_token()}}";

        $(function() {

            hiddenTableDisciplinasModulo();

            $('#localizar').on('click', function(e) {
                var disciplina = $('#disciplina').val();

                if(disciplina)
                {
                    $.harpia.httpget('{{url('/')}}/academico/async/disciplinas/findbynome/' + matriz + '/' + disciplina).done(function(data) {
                        renderTableLocalizadas(data);
                    });
                }

                return false;
            });

            function renderTableLocalizadas(data)
            {
                var body = $('#localizadas tbody');
                var div_table = $('#table-localizadas');
                var mensagem = $('#mensagem-localizadas');

                body.empty();

                if(data.length)
                {
                    // ESCONDER MENSAGEM
                    mensagem.addClass('hidden');

                    // Mostrar Tabela
                    div_table.removeClass('hidden');

                    for(var i = 0; i < data.length; i++)
                    {
                        var newRow = $("<tr>");
                        var cols = "";

                        cols += '<td id="dis_id">'+data[i].dis_id+'</td>';
                        cols += '<td id="nome">'+data[i].dis_nome+'</td>';
                        cols += '<td id="nivel">'+data[i].nvc_nome+'</td>';
                        cols += '<td id="cargahoraria">'+data[i].dis_carga_horaria+'</td>';
                        cols += '<td id="creditos">'+data[i].dis_creditos+'</td>';
                        cols += '<td><div class="form-group">';
                        cols += '<select class="form-control" id="mdc_tipo_avaliacao">';
                        cols += '<option value="numerica" selected>NUMERICA</option>';
                        cols += '<option value="conceitual">CONCEITUAL</option>';
                        cols += '</select></div></td>';

                        cols += '<td>';
                        cols += '<button class="btn btn-success btn-add-disciplina" type="button"><i class="fa fa-plus"></i> Adicionar ao módulo</button>';
                        cols += '</td>';

                        newRow.append(cols);

                        body.append(newRow);
                    }
                } else {
                    // esconder tabela
                    div_table.addClass('hidden');
                    // mostrar mensagem
                    mensagem.removeClass('hidden');
                }

                body.closest('.box').removeClass('hidden');
            }

            $(document).on('click','.btn-add-disciplina', function (e) {

                e.currentTarget.setAttribute("disabled", true);

                var linha = $(this).closest('tr');

                addDisciplinaIntoMatrizCurricular(linha);
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

                        var mdc_id = button.closest('form').find('input[name="id"]').val();
                        var linha = button.closest('tr');

                        var data = {mdc_id : mdc_id,mtc_id: matriz, _token : csrf_token};

                        $.ajax({
                            type: 'POST',
                            url: '/academico/async/modulosdisciplinas/deletardisciplina',
                            data: data,
                            success: function (data) {
                                toastr.success('Disciplina excluída com sucesso!', null, {progressBar: true});
                                linha.remove();
                                hiddenTableDisciplinasModulo();
                            },
                            error: function (xhr, textStatus, error) {
                                switch (xhr.status) {
                                    case 400:
                                        toastr.error('Não é possível adicionar uma disciplina mais de uma vez para uma mesma matriz.', null, {progressBar: true});
                                        break;
                                    default:
                                        toastr.error(xhr.responseText, null, {progressBar: true});
                                }
                            }
                        });
                    }
                });

            });

            var addDisciplinaIntoMatrizCurricular = function(linha) {

                var disciplinaSelecionada = new Array();

                disciplinaSelecionada['dis_id'] = linha.find('#dis_id').text();
                disciplinaSelecionada['nome'] = linha.find('#nome').text();
                disciplinaSelecionada['nivel'] = linha.find('#nivel').text();
                disciplinaSelecionada['cargahoraria'] = linha.find('#cargahoraria').text();
                disciplinaSelecionada['creditos'] = linha.find('#creditos').text();
                disciplinaSelecionada['mdc_tipo_avaliacao'] = linha.find('#mdc_tipo_avaliacao').val();

                var data = {
                    dis_id: linha.find('#dis_id').text(),
                    tipo_avaliacao: linha.find('#mdc_tipo_avaliacao').val(),
                    mtc_id: matriz,
                    mod_id: modulo,
                    _token: csrf_token
                };

                $.ajax({
                    type: 'POST',
                    url: '/academico/async/modulosdisciplinas/adicionardisciplina',
                    data: data,
                    success: function (data) {
                        toastr.success('Disciplina adicionada com sucesso!', null, {progressBar: true});

                        // Adiciona o id do retorno para a variavel que vai ser usada para montar a linha na tabela de disciplinas selecionadas
                        disciplinaSelecionada['mdc_id'] = data.mdc_id;

                        addLinhaTabelaSelecionadas(linha, disciplinaSelecionada);
                        hiddenTableDisciplinasModulo();
                    },
                    error: function (xhr, textStatus, error) {
                        switch (xhr.status) {
                            case 400:
                                toastr.error('Não é possível adicionar uma disciplina mais de uma vez para uma mesma matriz.', null, {progressBar: true});
                            break;
                            default:
                                toastr.error(xhr.responseText, null, {progressBar: true});
                        }
                    }
                });

            }

            var addLinhaTabelaSelecionadas = function(linha, disciplina) {

                linha.remove();

                var body = $('#selecionadas tbody');

                var newRow = $("<tr>");
                var column = "";

                column += '<td id="mdc_id">'+disciplina['mdc_id']+'</td>';
                column += '<td id="dis_id">'+disciplina['dis_id']+'<input type="hidden" value="'+disciplina['dis_id']+'" name="dis_id"></td>';
                column += '<td id="nome">'+disciplina['nome']+'</td>';
                column += '<td id="nivel">'+disciplina['nivel']+'</td>';
                column += '<td id="cargahoraria">'+disciplina['cargahoraria']+'</td>';
                column += '<td id="creditos">'+disciplina['creditos']+'</td>';
                column += '<td id="mdc_tipo_avaliacao">'+disciplina['mdc_tipo_avaliacao']+'<input type="hidden" value="'+disciplina['mdc_tipo_avaliacao']+'" name="disciplinas[mdc_tipo_avaliacao][]"></td>';

                column += '<td>';
                column += '<form action="" method="POST">'
                        +'<input type="hidden" name="id" value="'+disciplina['mdc_id']+'">'
                        +'<input type="hidden" name="_token" value="{{csrf_token()}}">'
                        +'<input type="hidden" name="_method" value="POST">'
                        +'<button class="btn-delete btn btn-danger btn-sm"><i class="fa fa-trash"></i> Excluir</button>'
                        +'</form>';
                column += '</td>';

                newRow.append(column);

                body.append(newRow);

                newRow.toggle("pulsate").fadeIn();
            }

            function hiddenTableDisciplinasModulo() {
                var table = $('#selecionadas');
                var box = table.closest('.box-body');
                var msg = box.find('#msg');
                var linhas = $('#selecionadas tbody tr');

                if(linhas.length > 0)
                {
                    table.removeClass('hidden');
                    msg.addClass('hidden');
                } else {
                    table.addClass('hidden');
                    msg.removeClass('hidden');
                }
            }
        });
    </script>
@endsection
