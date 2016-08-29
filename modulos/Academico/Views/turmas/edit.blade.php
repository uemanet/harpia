@extends('layouts.modulos.seguranca')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
@endsection

@section('title')
    Turmas
@stop

@section('subtitle')
    Alterar turma :: {{$turma->trm_nome}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de turma</h3>
        </div>
        <div class="box-body">
            {!! Form::model($turma,["url" => url('/') . "/academico/turmas/edit/$turma->trm_id", "method" => "PUT", "id" => "form", "role" => "form"]) !!}
                 @include('Academico::turmas.includes.formulario_edit')
            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{asset('/js/plugins/select2.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("select").select2();
        });
    </script>
@endsection
