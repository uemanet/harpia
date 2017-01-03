@extends('layouts.modulos.academico')

@section('title')
    Módulos
@stop

@section('subtitle')
    Gerenciamento de matrizes curriculares :: {{$curso->crs_nome}} :: {{$matrizcurricular->mtc_titulo}}
@stop

@section('actionButton')
    {!!ActionButton::render($actionButton)!!}
@stop

@section('content')

    @if(!$modulos->isEmpty())
        <div class="box-group" id="accordion">
        @foreach($modulos as $modulo)
          <div class="panel box box-primary">
            <div class="box-header with-border">
              <h4 class="box-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$modulo->mdo_id}}">
                  {{$modulo->mdo_nome}}
                </a>
              </h4>
                <div class="box-tools pull-right">
                    {!! ActionButton::grid([
                        'type' => 'LINE',
                        'buttons' => [
                        [
                            'classButton' => 'btn btn-box-tool',
                            'icon' => 'fa fa-pencil',
                            'action' => '/academico/modulosmatrizes/edit/'.$modulo->mdo_id,
                            'label' => 'Editar',
                            'method' => 'get'
                        ],
                        [
                            'classButton' => 'btn btn-box-tool btn-delete',
                            'icon' => 'fa fa-trash',
                            'action' => '/academico/modulosmatrizes/delete',
                            'id' => $modulo->mdo_id,
                            'label' => 'Excluir',
                            'method' => 'post'
                        ]
                    ]
                    ]) !!}
                </div>
            </div>
            <div id="collapse{{$modulo->mdo_id}}" class="panel-collapse collapse in">
              <div class="box-body">
                @if($disciplinas[$modulo->mdo_id]->count())
                    <table  class="table table-bordered table-hover">
                      <thead>
                      <th>Nome</th>
                      <th>Nível</th>
                      <th>Carga Horária</th>
                      <th>Créditos</th>
                      <th>Pré-Requisitos</th>
                      </thead>
                      <tbody>
                        @foreach($disciplinas[$modulo->mdo_id] as $disciplina)
                          <tr>
                            <td>{{$disciplina->dis_nome}}</td>
                            <td>{{$disciplina->nvc_nome}}</td>
                            <td>{{$disciplina->dis_carga_horaria}} horas</td>
                            <td>{{$disciplina->dis_creditos}}</td>
                            @if(!empty($disciplina->pre_requisitos))
                                <td>
                                    @foreach($disciplina->pre_requisitos as $pre)
                                        <p>{{$pre->dis_nome}}</p>
                                    @endforeach
                                </td>
                            @else
                                <td><p>Sem pré-requsitos</p></td>
                            @endif
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                @else
                  <p>
                    Sem disciplinas cadastradas
                  </p>
                @endif
              </div>
              <div class="box-footer">
                {!! ActionButton::grid([
                    'type' => 'LINE',
                    'buttons' => [
                        [
                            'classButton' => 'btn btn-success',
                            'icon' => 'fa fa-cogs',
                            'action' => '/academico/modulosmatrizes/gerenciardisciplinas/'.$modulo->mdo_id,
                            'label' => 'Gerenciar disciplinas do módulo',
                            'method' => 'get'
                        ],
                    ]
                ])
                !!}
              </div>
            </div>
          </div>
        @endforeach
        </div>
    @else
        <div class="box box-primary">
            <div class="box-body">Sem registros para apresentar</div>
        </div>
    @endif
@stop
