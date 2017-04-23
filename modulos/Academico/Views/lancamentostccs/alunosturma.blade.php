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
                        <th >Nome</th>
                        <th >Status</th>
                        <th ></th>
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
                                  @if($dado->mat_ltc_id == null and $dado->ltc_anx_tcc == null )
                                    {!! ActionButton::grid([
                                        'type' => 'LINE',
                                        'buttons' => [
                                            [
                                                'classButton' => 'btn btn-success',
                                                'icon' => 'fa fa-plus',
                                                'route' => 'academico.lancamentostccs.create',
                                                'parameters' => ['aluno' => $dado->alu_id,'turma' => $turma->trm_id],
                                                'label' => '',
                                                'method' => 'get'
                                            ]
                                        ]
                                    ]) !!}
                                  @endif

                                  @if($dado->mat_ltc_id != null and $dado->ltc_anx_tcc == null )
                                    {!! ActionButton::grid([
                                        'type' => 'LINE',
                                        'buttons' => [
                                            [
                                                'classButton' => 'btn btn-primary',
                                                'icon' => 'fa fa-edit',
                                                'route' => 'academico.lancamentostccs.edit',
                                                'parameters' => ['id' => $dado->mat_ltc_id],
                                                'label' => '',
                                                'method' => 'get'
                                            ]
                                        ]
                                    ]) !!}
                                  @endif

                                  @if($dado->mat_ltc_id != null and $dado->ltc_anx_tcc !== null )
                                    {!! ActionButton::grid([
                                        'type' => 'LINE',
                                        'buttons' => [
                                            [
                                                'classButton' => 'btn btn-primary',
                                                'icon' => 'fa fa-edit',
                                                'route' => 'academico.lancamentostccs.edit',
                                                'parameters' => ['id' => $dado->mat_ltc_id],
                                                'label' => '',
                                                'method' => 'get'
                                            ],
                                            [
                                                'classButton' => 'btn btn-warning docAnexo',
                                                'icon' => 'fa fa-download',
                                                'route' => 'academico.lancamentostccs.anexo',
                                                'parameters' => ['id' => $dado->ltc_id],
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
