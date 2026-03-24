@extends('layouts.modulos.default')

@section('title')
    Gerenciamento de Matriculas
@stop

@section('subtitle')
    {{$lista->lst_nome}} - {{$lista->lst_descricao}}
@stop

@section('actionButton')
    {!!ActionButton::render($actionButton)!!}
@stop

@section('content')
    <div class="row py-2">
        <div class="card card-primary card-outline">
            {{-- Removido a classe obsoleta with-border --}}
            <div class="card-header">
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
                    @if($turmas->count())
                        <input type="hidden" name="lst_id" value="{{ $lista->lst_id }}" id="lst_id" >
                        <div class="col-md-6">
                            <select name="trm_id" id="trm_id" class="form-control">
                                <option value="">Selecione uma Turma</option>
                                @foreach($turmas as $key => $value)
                                    <option value="{{ $key }}" {{ old('trm_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100" id="btnBuscar">Buscar</button>
                        </div>
                    @else
                        <div class="col-md-12">
                            <p>Não há matrículas nesta lista</p>
                        </div>
                    @endif
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
    <div class="row py-2" id="tabela"></div>
@stop

@section('scripts')
    <script type="text/javascript">
        $(function () {
            $('#btnBuscar').click(function (e) {

                var lista = $('#lst_id').val();
                var turma = $('#trm_id').val();

                if (!lista || !turma) {
                    return false;
                }

                renderTable(lista, turma);
            });

            $('#tabela').on('click', '.btnDelete', function (e) {
                e.preventDefault();

                var button = $(this);

                // Corrigido para a sintaxe moderna do SweetAlert2
                Swal.fire({
                    title: "Tem certeza que deseja excluir?",
                    text: "Você não poderá recuperar essa informação!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#dc3545",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Sim, pode excluir!",
                    cancelButtonText: "Não, quero cancelar!"
                }).then((result) => {
                    if (result.isConfirmed) {

                        var lista = $('#lst_id').val();
                        var matricula = button.data('mat-id');
                        var turma = $('#trm_id').val();
                        var token = "{{csrf_token()}}";

                        var data = {
                            lst_id: lista,
                            mat_id: matricula,
                            _token: token
                        };

                        $.harpia.showloading();

                        $.ajax({
                            type: 'POST',
                            url: '/academico/carteirasestudantis/deletematricula',
                            data: data,
                            success: function (response) {
                                $.harpia.hideloading();

                                toastr.success(response, null, {progressBar: true});
                                renderTable(lista, turma);
                            },
                            error: function (xhr) {
                                $.harpia.hideloading();

                                // Blindagem do erro de tipagem no Ajax
                                var errorMessage = "Erro ao processar a requisição.";
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                } else if (xhr.responseText) {
                                    errorMessage = xhr.responseText.replace(/\"/g, '');
                                }

                                toastr.error(errorMessage, null, {progressBar: true});
                            }
                        });
                    }
                });

            });

            function renderTable(listaId, turmaId) {
                $('#tabela').empty();

                $.ajax({
                    method: 'GET',
                    url: '/academico/async/carteirasestudantis/gettableshowmatriculas/'+listaId+'/'+turmaId,
                    success: function (res) {
                        $('#tabela').append(res);
                    },
                    error: function (xhr) {
                        // Blindagem do erro de tipagem no Ajax
                        var errorMessage = "Erro ao carregar a tabela.";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseText) {
                            errorMessage = xhr.responseText.replace(/\"/g, '');
                        }

                        toastr.error(errorMessage, null, {progressBar: true});
                    }
                });
            };
        });
    </script>
@stop