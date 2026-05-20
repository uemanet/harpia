@extends('layouts.modulos.default')

@section('title')
    Sincronização
@stop

@section('subtitle')
    Módulo de Integração
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline p-0">
            <div class="card-header with-border">
                <h3 class="card-title"><i class="fa fa-filter"></i> Filtrar dados</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <form method="GET" class="d-flex w-100" action="{{{ route('integracao.sincronizacao.index') }}}">
                        <div class="col-md-6 px-1">
                            <input type="text" class="form-control" name="sym_table" id="sym_table" value="{{Request::input('sym_table')}}" placeholder="Nome da tabela">
                        </div>
                        <div class="col-md-4 px-1">
                          <select class="form-control" id="sym_status" name="sym_status">
                            <option selected="selected" disabled="disabled" hidden="hidden" value="">Escolha um status</option>
                            <option value="1">Pendente</option>
                            <option value="2">Sucesso</option>
                            <option value="3">Falha</option>
                          </select>
                        </div>
                        <div class="col-md-2 px-1">
                            <input type="submit" class="btn btn-primary w-100" value="Buscar">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>

    <div class="row">
        <div class="card card-primary card-outline my-2 p-0">
            @if(!is_null($tabela))
                <div class="card-body p-0">
                    {!! $tabela->render() !!}
                </div>
                <div class="card-footer clearfix">
                    {!! $paginacao->links('pagination::bootstrap-4') !!}
                </div>
            @else
                <div class="card-body">
                    Sem registros para apresentar
                </div>
            @endif
        </div>
    </div>
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
