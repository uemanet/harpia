@extends('layouts.modulos.integracao')

@section('title')
    Lançamento de TCC
@stop

@section('subtitle')
    {{$turma->trm_nome}}
@stop

@section('content')
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Disciplina: {{$disciplina->dis_nome}}</h3>
    </div>

    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
            @if(count($dados))
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <th style="width: 10px">Matrícula</th>
                        <th style="width: 10px">Nome</th>
                        <th style="width: 10px">Status</th>
                        <th style="width: 20px"></th>
                    </thead>
                    <tbody>
                        @foreach($dados as $dado)
                            <tr>
                                <td>{{$dado->mat_id}}</td>
                                <td>{{$dado->pes_nome}}</td>
                                <td>
                                  @if($dado->mat_ltc_id == null)
                                      <span class="label label-info">Tcc não lançado</span>
                                  @else
                                      <span class="label label-success">Tcc lançado</span>
                                  @endif</td>
                                <td>
                                  @if($dado->mat_ltc_id == null)
                                    {!! ActionButton::grid([
                                        'type' => 'LINE',
                                        'buttons' => [
                                            [
                                                'classButton' => 'btn btn-success',
                                                'icon' => 'fa fa-plus',
                                                'action' => '/academico/lancamentostccs/create/'.$dado->alu_id.'/'.$turma->trm_id,
                                                'label' => '',
                                                'method' => 'get'
                                            ]
                                        ]
                                    ]) !!}
                                  @else
                                    {!! ActionButton::grid([
                                        'type' => 'LINE',
                                        'buttons' => [
                                            [
                                                'classButton' => 'btn btn-primary',
                                                'icon' => 'fa fa-edit',
                                                'action' => '/academico/lancamentostccs/edit/'.$dado->mat_ltc_id,
                                                'label' => '',
                                                'method' => 'get'
                                            ]
                                        ]
                                    ]) !!}
                                  @endif
                                </td>
                            </tr>
                          @endforeach

                    </tbody>
                </table>
            @else
                <p>Sem turmas com disciplina de TCC ativa</p>
            @endif
            </div>
        </div>
    </div>
</div>

@stop
