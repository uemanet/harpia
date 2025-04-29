@extends('layouts.modulos.academico')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection

@section('title')
    Importação
@stop

@section('subtitle')
    Importação de Usuários
@stop

@section('content')
    @section('content')
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Formulário de importação de Pessoas</h3>
            </div>
            <div class="box-body">
                {!! Form::open(["route" => ['academico.importacoesusuarios.importar'], "method" => "POST", "id" => "form", "role" => "form", "enctype" => "multipart/form-data"]) !!}
                @include('Academico::importacaousuario.includes.formulario')
                {!! Form::close() !!}
            </div>
        </div>
    @stop

@stop


@section('scripts')
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("select").select2();
        });
    </script>
@endsection
