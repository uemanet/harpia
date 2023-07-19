@extends('layouts.modulos.academico')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
    <link rel="stylesheet" href="{{asset('/css/plugins/datepicker3.css')}}">
@endsection

@section('title')
    Mediadores do grupo
@stop

@section('subtitle')
    Vínculo de mediadores
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de vínculo de mediadores</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["route" => 'academico.ofertascursos.turmas.grupos.tutoresgrupos.create', "method" => "POST", "id" => "form", "role" => "form"]) !!}
                @include('Academico::tutoresgrupos.includes.formulario')
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


@section('scripts')



@endsection
