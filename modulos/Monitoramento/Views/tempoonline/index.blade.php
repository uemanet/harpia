@extends('layouts.modulos.integracao')

@section('title')
    Serviços do Ambiente Virtual
@stop

@section('subtitle')

@stop

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Visualize tempo online de tutores dos ambientes abaixo</h3>
    </div>

    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
            @if(count($ambientes))
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <th style="width: 10px">#</th>
                        <th style="width: 10px">Ambiente</th>
                        <th style="width: 20px">Url</th>
                        <th style="width: 20px"></th>
                    </thead>
                    <tbody>
                        @foreach($ambientes as $ambiente)
                            <tr>
                                <td>{{$ambiente->amb_id}}</td>
                                <td>{{$ambiente->amb_nome}}</td>
                                <td>{{$ambiente->amb_url}}</td>
                                <td>
                                    {!! ActionButton::grid([
                                        'type' => 'LINE',
                                        'buttons' => [
                                            [
                                                'classButton' => 'text-blue',
                                                'icon' => 'fa fa-eye',
                                                'route' => 'monitoramento.tempoonline.monitorar',
                                                'parameters' => ['id' => $ambiente->amb_id],
                                                'label' => '',
                                                'method' => 'get'
                                            ]
                                        ]
                                    ]) !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Sem serviços adicionados ao ambiente virtual</p>
            @endif
            </div>
        </div>
    </div>
</div>

@stop
