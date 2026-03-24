@extends('layouts.modulos.default')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">

@endsection

@section('title')
    Alterar tutor
@stop

@section('subtitle')
Tutor atual: {{$tutor->pessoa->pes_nome}}
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de alteração de tutor do grupo. </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('academico.ofertascursos.turmas.grupos.tutoresgrupos.alterartutor', [$tutorgrupo->ttg_id]) }}" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $tutorgrupo - inputs devem usar old('campo', $tutorgrupo->campo) --}}
                  @include('Academico::tutoresgrupos.includes.formulario_alterar')
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
