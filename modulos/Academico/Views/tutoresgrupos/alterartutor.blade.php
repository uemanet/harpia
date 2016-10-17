@extends('layouts.modulos.seguranca')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
    <link rel="stylesheet" href="{{asset('/css/plugins/datepicker3.css')}}">
@endsection

@section('title')
    Alterar tutor
@stop

@section('subtitle')
Tutor atual: {{$tutor->pessoa->pes_nome}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de alteração de tutor do grupo. </h3>
        </div>
        <div class="box-body">
            {!! Form::model($tutorgrupo,["url" => url('/') . "/academico/tutoresgrupos/alterartutor/$tutorgrupo->ttg_id", "method" => "PUT", "id" => "form", "role" => "form"]) !!}
                  @include('Academico::tutoresgrupos.includes.formulario_alterar')
            {!! Form::close() !!}
        </div>
    </div>
@stop


@section('scripts')
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/plugins/bootstrap-datepicker.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/plugins/bootstrap-datepicker.pt-BR.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
            $(document).ready(function() {
                $("select").select2();
            });
    </script>

    <script type="text/javascript">
        $('.datepicker').datepicker({
           format: 'dd/mm/yyyy',
           language: 'pt-BR'
        });
    </script>
@endsection
