@extends('layouts.modulos.integracao')

@section('title')
    Ambientes virtuais disponíveis
@stop

@section('subtitle')

@stop

@section('content')
    <div class="row">
        @if(count($ambientes))
            @foreach($ambientes as $ambiente)
                <div class="col-md-4">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h4>{{$ambiente->amb_nome}}</h4>

                            <p>Moodle {{$ambiente->amb_versao}}</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa fa-line-chart"></i>
                        </div>
                        @haspermission('monitoramento.tempoonline.monitorar')
                        <a href="{{route('monitoramento.tempoonline.monitorar', $ambiente->amb_id)}}"
                           class="small-box-footer">Acessar <i class="fa fa-arrow-circle-right"></i></a>
                        @endhaspermission
                    </div>
                </div>
            @endforeach
        @else
            <p>Sem serviços adicionados ao ambiente virtual</p>
        @endif
    </div>
@stop
