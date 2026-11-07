@extends('layouts.modulos.default')

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
                    <div class="small-box text-bg-primary">
                        <div class="inner">
                            <h4>{{$ambiente->amb_nome}}</h4>

                            <p>Moodle {{$ambiente->amb_versao}}</p>
                        </div>

                        <i class="small-box-icon fa fa fa-line-chart"></i>

                        @haspermission('monitoramento.forumresponse.monitorar')
                            <a
                                    href="{{route('monitoramento.forumresponse.monitorar', $ambiente->amb_id)}}"
                                    class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover"
                            >
                                Acessar <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        @endhaspermission
                    </div>
                </div>
            @endforeach
        @else
            <p>Sem serviços adicionados ao ambiente virtual</p>
        @endif
    </div>
@stop
