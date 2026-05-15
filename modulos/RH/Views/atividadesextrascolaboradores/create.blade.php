@extends('layouts.modulos.default')

@section('breadcrumbs')
    {{ Breadcrumbs::render('rh.colaboradores.atividadesextrascolaboradores.create', $colaborador->col_id) }}
@endsection

@section('stylesheets')
    <link rel="stylesheet" href="{{asset('/css/plugins/select2.css')}}">
    <link rel="stylesheet" href="{{asset('/css/plugins/datepicker3.css')}}">
@endsection

@section('title')
    Atividades Extras
@stop

@section('subtitle')
    Cadastro Atividades Extras
@stop

@section('content')
    <div class="card card-success card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de cadastro de Atividade Extra</h3>
        </div>
        <form action="{{ route('rh.colaboradores.atividadesextrascolaboradores.create', [$colaborador->col_id]) }}" method="POST" id="form" role="form">
            @csrf
            <div class="card-body">
                @include('RH::atividadesextrascolaboradores.includes.formulario')
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
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
