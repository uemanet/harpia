@extends('layouts.interno')

@section('title')
    Recursos
@stop

@section('subtitle')
    Módulo de Segurança
@stop

@section('actionButton')
    {!!ActionButton::render($actionButton)!!}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-filter"></i> Filtrar dados</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <form method="GET" action="{{ url('/seguranca/recursos/index') }}">
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="rcs_nome" id="rcs_nome" value="{{Input::get('rcs_nome')}}" placeholder="Nome do recurso">
                    </div>
                    <div class="col-md-3">
                        <input type="submit" class="form-control btn-primary" value="Buscar">
                    </div>
                </form>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    @if($tableData->count())
        <div class="box box-primary">
            <div class="box-header">
                {!!
                    $tableData->columns(array(
                        'rcs_id' => '#',
                        'rcs_nome' => 'Recurso',
                        'rcs_descricao' => 'Descrição',
                        'rcs_action' => 'Ações'
                    ))
                    ->modifyCell('rcs_action', function() {
                        return array('style' => 'width: 140px;');
                    })
                    ->means('rcs_action', 'rcs_id')
                    ->modify('rcs_action', function($id) {
                        return ActionButton::grid([
                                'type' => 'SELECT',
                                'config' => [
                                    'classButton' => 'btn-default',
                                    'label' => 'Selecione'
                                ],
                                'buttons' => [
                                    [
                                        'classButton' => '',
                                        'icon' => 'fa fa-pencil',
                                        'action' => '/seguranca/recursos/edit/' . $id,
                                        'label' => 'Editar',
                                        'method' => 'get'
                                    ],
                                    [
                                        'classButton' => 'btn-delete text-red',
                                        'icon' => 'fa fa-trash',
                                        'action' => '/seguranca/recursos/delete',
                                        'id' => $id,
                                        'label' => 'Excluir',
                                        'method' => 'post'
                                    ]
                                ]
                            ]);
                    })
                    ->sortable(array('rcs_id', 'rcs_nome'))
                    ->render()
                !!}
            </div>
        </div>

        <div class="text-center">{!! $tableData->appends(Input::except('page'))->links() !!}</div>
    @endif
@stop