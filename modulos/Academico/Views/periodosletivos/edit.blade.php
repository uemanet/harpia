@extends('layouts.modulos.academico')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/datepicker3.css')}}">
@endsection

@section('title')
    Períodos Letivos
@stop

@section('subtitle')
    Alterar período letivo :: {{$periodoLetivo->per_id}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de períodos letivos</h3>
        </div>
        <div class="box-body">
            {!! Form::model($periodoLetivo, ["route" => ['academico.periodosletivos.edit',$periodoLetivo->per_id], "method" => "PUT", "id" => "form", "role" => "form"]) !!}
                @include('Academico::periodosletivos.includes.formulario')
            {!! Form::close() !!}
        </div>
    </div>
@stop


@section('scripts')
    <script src="{{asset('/js/plugins/bootstrap-datepicker.js')}}" type="text/javascript"></script>
    <script src="{{asset('/js/plugins/bootstrap-datepicker.pt-BR.js')}}" type="text/javascript"></script>

    <script type="text/javascript">
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            language: 'pt-BR'
        });
    </script>
@endsection