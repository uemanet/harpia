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
                {!! Form::hidden('lst_id', $lista->lst_id, ['id' => 'lst_id']) !!}
                <div class="col-md-5">
                    {!! Form::select('trm_id', $turmas, old('trm_id'), ['id' => 'trm_id', 'class' => 'form-control', 'placeholder' => 'Turma']) !!}
                </div>
                <div class="col-md-1">
                    <button class="form-control btn-primary btnBuscar">Buscar</button>
                </div>
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
                $('.tabela').empty();

                 var lista = $('#lst_id').val();
                 var turma = $('#trm_id').val();

                 if (!lista || !turma) {
                     return false;
                 }

                 $.ajax({
                     method: 'GET',
                     url: '/academico/async/carteirasestudantis/gettableshowmatriculas/'+lista+'/'+turma,
                     success: function (res) {
                         $('.tabela').append(res);
                     },
                     error: function (res) {
                         toastr.error(res.responseText.replace(/\"/g, ''), null, {progressBar: true});
                     }
                 });
            });
        });
    </script>
@stop
