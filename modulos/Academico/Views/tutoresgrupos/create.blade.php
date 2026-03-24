@extends('layouts.modulos.default')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">

@endsection

@section('title')
    Tutores do grupo
@stop

@section('subtitle')
    Vínculo de tutores
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de vínculo de tutores</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('academico.ofertascursos.turmas.grupos.tutoresgrupos.create') }}" method="POST" id="form" role="form">
    @csrf
                @include('Academico::tutoresgrupos.includes.formulario')
            </form>
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


@endsection


@section('scripts')



@endsection
