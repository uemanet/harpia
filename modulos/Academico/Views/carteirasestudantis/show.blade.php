@extends('layouts.modulos.academico')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection

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
                @if($turmas->count())
                    {!! Form::hidden('lst_id', $lista->lst_id, ['id' => 'lst_id']) !!}
                    <div class="col-md-4">
                        {!! Form::select('trm_id', $turmas, old('trm_id'), ['id' => 'trm_id', 'class' => 'form-control', 'placeholder' => 'Selecione uma Turma']) !!}
                    </div>
                    <div class="col-md-2">
                        <button class="form-control btn-primary btnBuscar">Buscar</button>
                    </div>
                @else
                    <div class="col-md-12">
                        <p>Não há matrículas nesta lista</p>
                    </div>
                @endif
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <div class="tabela"></div>
@stop

@section('scripts')
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        $(function () {
            $('select').select2();

            $('.btnBuscar').click(function (e) {

                 var lista = $('#lst_id').val();
                 var turma = $('#trm_id').val();

                 if (!lista || !turma) {
                     return false;
                 }

                 renderTable(lista, turma);
            });

            $('.tabela').on('click', '.btnDelete', function (e) {
                e.preventDefault();

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
                    }
                });

            });

            function renderTable(listaId, turmaId) {
                $('.tabela').empty();

                $.ajax({
                    method: 'GET',
                    url: '/academico/async/carteirasestudantis/gettableshowmatriculas/'+listaId+'/'+turmaId,
                    success: function (res) {
                        $('.tabela').append(res);
                    },
                    error: function (res) {
                        toastr.error(res.responseText.replace(/\"/g, ''), null, {progressBar: true});
                    }
                });
            };
        });
    </script>
@stop
