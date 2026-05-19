@extends('layouts.modulos.default')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
    <link rel="stylesheet" href="{{asset('/css/plugins/datepicker3.css')}}">
@endsection

@section('title')
    Período Laboral
@stop

@section('subtitle')
    Edição de período laboral
@stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title m-0">Formulário de edição de períodos laborais</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('rh.periodoslaborais.edit', [$periodolaboral->pel_id]) }}" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $periodolaboral - inputs devem usar old('campo', $periodolaboral->campo) --}}
                @include('RH::periodoslaborais.includes.formulario')
            </form>
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
