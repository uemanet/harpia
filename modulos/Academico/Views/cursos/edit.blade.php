@extends('layouts.modulos.seguranca')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
    <link rel="stylesheet" href="{{asset('/css/plugins/datepicker3.css')}}">
@endsection

@section('title')
    Curso
@stop

@section('subtitle')
    Alterar curso :: {{$curso->crs_nome}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de curso</h3>
        </div>
        <div class="box-body">
            {!! Form::model($curso,["route" => ['academico.cursos.edit',$curso->crs_id], "method" => "PUT", "id" => "form", "role" => "form"]) !!}
            @include('Academico::cursos.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/plugins/bootstrap-datepicker.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/plugins/bootstrap-datepicker.pt-BR.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("select").select2();

            var mediaAprovacao = $('#media_min_aprovacao').val();
            var mediaFinal = $('#media_min_final').val();
            var aprovacaoFinal = $('#media_min_aprovacao_final').val();
            var modoRecuperacao = $('#modo_recuperacao').val();

            $('#form').submit(function (event) {
                event.preventDefault();

                var form = this;
                var formMediaAprovacao = $('#media_min_aprovacao').val();
                var formMediaFinal = $('#media_min_final').val();
                var formAprovacaoFinal = $('#media_min_aprovacao_final').val();
                var formModoRecuperacao = $('#modo_recuperacao').val();

                if ((mediaAprovacao !== formMediaAprovacao) || (mediaFinal !== formMediaFinal)
                    || (aprovacaoFinal !== formAprovacaoFinal) || (modoRecuperacao !== formModoRecuperacao)) {

                    event.preventDefault();
                    swal({
                        title: "Alterar configurações de notas do curso",
                        text: "Alterar as configurações de notas do curso exige que as notas das turmas relativas ao curso sejam recalculadas manualmente. Deseja continuar ?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Sim, alterar configurações",
                        cancelButtonText: "Não, quero cancelar!",
                        closeOnConfirm: true
                    }, function (isConfirm) {
                        if (isConfirm) {
                            form.submit();
                        }
                    });
                } else {
                    form.submit();
                }
            })
        });

        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR'
        });
    </script>
@endsection
