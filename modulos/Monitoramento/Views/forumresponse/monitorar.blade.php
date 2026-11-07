@extends('layouts.modulos.default')

@section('title')
    Monitoramento de Respostas nos Fóruns
@stop

@section('subtitle')
    {{$ambiente->amb_nome}}
@stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Visualização de dados do ambiente virtual</h3>
        </div>
        <div class="card-body">
            @include('Monitoramento::forumresponse.includes.formulario')
        </div>
        <div class="text-center margin" id="grafico"></div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary hidden" id="cardTutores">
                <!-- /.card-header -->
                <div class="card-body"></div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{asset('/js/plugins/Chart.min.js')}}" type="text/javascript"></script>
@endsection
