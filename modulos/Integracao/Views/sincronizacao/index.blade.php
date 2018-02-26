@extends('layouts.modulos.integracao')

@section('title')
    Sincronização
@stop

@section('subtitle')
    Módulo de Integração
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
                <form method="GET" action="{{ route('integracao.sincronizacao.index') }}">
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="sym_table" id="sym_table" value="{{Input::get('sym_table')}}" placeholder="Nome da tabela">
                    </div>
                    <div class="col-md-3">
                      <select class="form-control" id="sym_status" name="sym_status">
                        <option selected="selected" disabled="disabled" hidden="hidden" value="">Escolha um status</option>
                        <option value="1">Pendente</option>
                        <option value="2">Sucesso</option>
                        <option value="3">Falha</option>
                      </select>
                    </div>
                    <div class="col-md-3">
                        <input type="submit" class="form-control btn-primary" value="Buscar">
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
                <div class="col-md-3 pull-right">
                  <div class="form-group">
                    <button class="btn btn-success form-control btn-mapear">
                      <i class="fa fa-exchange"></i> Migrar Todos os erros
                    </button>
                  </div>
                </div>
              </div>
                {!! $tabela->render() !!}
            </div>
        </div>

        <div class="text-center">{!! $paginacao->links() !!}</div>

    @else
        <div class="box box-primary">
            <div class="box-body">Sem registros para apresentar</div>
        </div>
    @endif
@stop

@section('scripts')
    <script>
        $(function() {
            $(document).on('click', '.btn-mapear', function (event) {
              //Pega todas as linhas da tabela que estão com status de falha
              itens = new Array();
              $('table tbody tr').each(function () {

                  var colunas = $(this).children();
                  var item = {
                      'id': trim($(colunas[0]).text()),
                      'status': trim($(colunas[3]).text())

                  };

                  // Adicionar o objeto item no array
                  if (item.status === "Falha") {
                    itens.push(item.id);
                  }
              });

              // listando os items (teste)
              console.info(itens);

              var dados = {
                ids: itens,
                _token: "{{csrf_token()}}"
              };

              var result = false;
              $.harpia.showloading();

              $.ajax({
                type: 'POST',
                url: '/integracao/async/sincronizacao/sincronizar',
                data: dados,
                success: function (response) {
                  $.harpia.hideloading();

                  toastr.success('Sincronização efetuada com sucesso!', null, {progressBar: true});

                },
                error: function (xhr, textStatus, error) {
                  $.harpia.hideloading();

                  switch (xhr.status) {
                    case 400:
                    toastr.error(xhr.responseText.replace(/\"/g, ''), null, {progressBar: true});
                    break;
                    default:
                    toastr.error(xhr.responseText.replace(/\"/g, ''), null, {progressBar: true});

                    result = false;
                  }
                }
              });

            });
        });

        function trim(str) {
            return str.replace(/^\s+|\s+$/g,"");
        }

    </script>
@stop
