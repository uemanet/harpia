@extends('layouts.modulos.default')

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
    <link rel="stylesheet" href="{{asset('/css/plugins/datepicker3.css')}}">
@endsection

@section('title')
    Contas de Colaborador
@stop

@section('subtitle')
    Alterar conta de colaborador :: {{$conta_colaborador->ccb_conta}}
@stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title m-0">Formulário de Edição de Conta de Colaborador</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('rh.colaboradores.contascolaboradores.edit', [$conta_colaborador->ccb_id]) }}" method="POST" id="form" role="form">
    @csrf
    @method('PUT')
    {{-- Form model: $conta_colaborador - inputs devem usar old('campo', $conta_colaborador->campo) --}}
            <input type="hidden" name="ccb_col_id" value="{{ $conta_colaborador->colaborador->col_id }}" >
            @include('RH::contascolaboradores.includes.formulario')
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
