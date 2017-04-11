@extends('layouts.modulos.seguranca')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection

@section('title')
    Vínculos
@stop

@section('subtitle')
    Adicionar vínculo :: Usuário : <b>{{$user->usr_usuario}}</b>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de cadastro de vínculos</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["url" => url('/') . "/academico/usuarioscursos/create/" . $usuario, "method" => "POST", "id" => "form", "role" => "form"]) !!}
            @include('Academico::vinculos.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("select").select2();
        });
    </script>

    <script type="application/javascript">
        $(document).ready(function(){
            $('#cursos[]').prop('selectedIndex',0);
        });
    </script>

@endsection
