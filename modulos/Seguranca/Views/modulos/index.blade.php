@extends('layouts.interno')

@section('title')
    Módulo de Segurança
@stop

@section('subtitle')
    Gerenciamento de módulos
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
                <form method="GET" action="{{ url('/seguranca/modulos/index') }}">
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="mod_nome" id="mod_nome" value="{{Input::get('mod_nome')}}" placeholder="Módulo">
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
                        'mod_id' => '#',
                        'mod_nome' => 'Módulo',
                        'mod_descricao' => 'Descrição',
                        'mod_action' => 'Ações'
                    ))
                    ->means('mod_action', 'mod_id')
                    ->modify('mod_action', function($id) {
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
                                        'action' => 'modulos/edit/' . $id,
                                        'label' => 'Editar',
                                        'target' => ''
                                    ],
                                    [
                                        'classButton' => 'btn-delete text-red',
                                        'icon' => 'fa fa-trash',
                                        'action' => 'modulos/delete/' . $id,
                                        'label' => 'Excluir',
                                        'target' => ''
                                    ]
                                ]
                            ]);
                    })
                    ->sortable(array('mod_id', 'mod_nome'))
                    ->render()
                !!}
            </div>
        </div>

        <div class="text-center">{!! $tableData->appends(Input::except('page'))->links() !!}</div>
    @endif
@stop