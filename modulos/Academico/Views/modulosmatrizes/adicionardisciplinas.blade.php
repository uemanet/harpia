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
                <table class="table table-bordered" id="localizadas">
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

    <div class="box box-primary hidden">
        <div class="box-header with-border">
            <h3 class="box-title">
                Disciplinas Selecionadas
            </h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <form action="{{route('academico.modulosmatrizes.postAdicionardisciplinas', ['id' => $modulo])}}" method="POST">
                {{csrf_field()}}
            <table class="table table-bordered" id="selecionadas">
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
            <div id="salvar" class="col-md-2 pull-right hidden">
                <button type="submit" id="btnSalvar" data-mdo-id="{{$modulo}}" class="btn btn-primary btn-block">Salvar</button>
            </div>
            </form>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        $(function() {
            $(document).on('click', '#localizar', function(e) {
               var disciplina = $('#disciplina').val();

                if(disciplina)
                {
                    $.harpia.httpget('{{url('/')}}/academico/async/disciplinas/findbynome/' + disciplina).done(function(data) {
                       renderTableLocalizadas(data);
                    });
                }

                return false;
            });

            $(document).on('click','.btn-success', function (e) {
                var linha = $(this).closest('tr');

                var elementos = new Array();

                elementos['dis_id'] = linha.find('#dis_id').text();
                elementos['nome'] = linha.find('#nome').text();
                elementos['nivel'] = linha.find('#nivel').text();
                elementos['cargahoraria'] = linha.find('#cargahoraria').text();
                elementos['creditos'] = linha.find('#creditos').text();
                elementos['mdc_tipo_avaliacao'] = linha.find('#mdc_tipo_avaliacao').val();

                renderTableSelecionadas(elementos);

                linha.remove();
            });

            $(document).on('click', '.btn-danger', function (e) {
                var linha = $(this).closest('tr');

                var elementos = new Array();

                elementos['dis_id'] = linha.find('#dis_id').text();
                elementos['nome'] = linha.find('#nome').text();
                elementos['nivel'] = linha.find('#nivel').text();
                elementos['cargahoraria'] = linha.find('#cargahoraria').text();
                elementos['creditos'] = linha.find('#creditos').text();

                renderTableElementosToLocalizadas(elementos);

                linha.remove();

                if($('#selecionadas tbody').find('tr').length)
                {
                    $('#selecionadas tbody').closest('.box').removeClass('hidden');
                    $('#salvar').removeClass('hidden');
                } else {
                    $('#selecionadas tbody').closest('.box').addClass('hidden');
                    $('#salvar').addClass('hidden');
                }
            });

            {{--$(document).on('click', '#btnSalvar', function (e) {--}}

                {{--var disciplinas = new Array();--}}
                {{--var button = $(this);--}}

                {{--$('#selecionadas tbody tr').each(function() {--}}
                    {{--var disc = {--}}
                        {{--dis_id: $(this).find('#dis_id').text(),--}}
                        {{--mdc_tipo_avaliacao: $(this).find('#mdc_tipo_avaliacao').text(),--}}
                        {{--mdo_id: button.attr('data-mdo-id')--}}
                    {{--};--}}

                   {{--disciplinas.push(disc);--}}
                {{--});--}}

                {{--var data = JSON.stringify(disciplinas);--}}
                {{--$.post('{{url('/')}}/academico/modulosmatrizes/adicionardisciplinas', data)--}}
                        {{--.done(function (response) {--}}
                            {{--console.log(response);--}}
                            {{--if(response.redirect) {--}}
                                {{--window.location.href = response.redirect;--}}
                            {{--}--}}
                        {{--});--}}
            {{--});--}}

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
                        cols += '<option value="NOTAS" selected>NOTAS</option>';
                        cols += '<option value="CONCEITO">CONCEITO</option>';
                        cols += '</select></div></td>';

                        cols += '<td>';
                        cols += '<button class="btn btn-success" type="button">Adicionar</button>';
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

            function renderTableSelecionadas(elementos) {
                var body = $('#selecionadas tbody');

                var newRow = $("<tr>");
                var cols = "";

                cols += '<td id="dis_id">'+elementos['dis_id']+'<input type="hidden" value="'+elementos['dis_id']+'" name="disciplinas[dis_id][]"></td>';
                cols += '<td id="nome">'+elementos['nome']+'</td>';
                cols += '<td id="nivel">'+elementos['nivel']+'</td>';
                cols += '<td id="cargahoraria">'+elementos['cargahoraria']+'</td>';
                cols += '<td id="creditos">'+elementos['creditos']+'</td>';
                cols += '<td id="mdc_tipo_avaliacao">'+elementos['mdc_tipo_avaliacao']+'<input type="hidden" value="'+elementos['mdc_tipo_avaliacao']+'" name="disciplinas[mdc_tipo_avaliacao][]"></td>';

                cols += '<td>';
                cols += '<button class="btn btn-danger" type="button">Remover</button>';
                cols += '</td>';

                newRow.append(cols);

                body.append(newRow);

                if(body.find('tr').length)
                {
                    body.closest('.box').removeClass('hidden');
                    $('#salvar').removeClass('hidden');
                }
            }

            function renderTableElementosToLocalizadas(elementos)
            {
                var body = $('#localizadas tbody');

                var newRow = $("<tr>");
                var cols = "";

                cols += '<td id="dis_id">'+elementos['dis_id']+'</td>';
                cols += '<td id="nome">'+elementos['nome']+'</td>';
                cols += '<td id="nivel">'+elementos['nivel']+'</td>';
                cols += '<td id="cargahoraria">'+elementos['cargahoraria']+'</td>';
                cols += '<td id="creditos">'+elementos['creditos']+'</td>';
                cols += '<td><div class="form-group">';
                cols += '<select class="form-control" id="mdc_tipo_avaliacao">';
                cols += '<option value="NOTAS" selected>NOTAS</option>';
                cols += '<option value="CONCEITO">CONCEITO</option>';
                cols += '</select></div></td>';

                cols += '<td>';
                cols += '<button class="btn btn-success" type="button">Adicionar</button>';
                cols += '</td>';

                newRow.append(cols);

                body.append(newRow);
            }
        });


    </script>
@endsection
