@extends('layouts.modulos.geral')

@section('title')
    Polos
@stop

@section('subtitle')
    Gerenciamento de polos
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
                <form method="GET" action="{{ url('/geral/polos/index') }}">
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="pol_nome" id="pol_nome" value="{{Input::get('pol_nome')}}" placeholder="Nome">
                    </div>
                    <div class="col-md-3">
                        <input type="submit" class="form-control btn-primary" value="Buscar">
                    </div>
                </form>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <div class="box box-primary">
        <div class="box-header">
            @if($tableData->count())
                {!!
                    $tableData->columns(array(
                        'pol_id' => '#',
                        'pol_nome' => 'Nome',
                        'action' => 'Ações'
                    ))
                    ->modifyCell('action', function() {
                        return array('style' => 'width: 140px;');
                    })
                    ->means('action', 'pol_id')
                    ->modify('action', function($id) {
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
                                        'action' => '/geral/polos/edit/' . $id,
                                        'label' => 'Editar',
                                        'method' => 'get'
                                    ],
                                    [
                                        'classButton' => 'btn-delete text-red',
                                        'icon' => 'fa fa-trash',
                                        'action' => '/geral/polos/delete',
                                        'id' => $id,
                                        'label' => 'Excluir',
                                        'method' => 'post'
                                    ]
                                ]
                            ]);
                    })
                    ->sortable(array('pol_id', 'pol_nome'))
                    ->render()
                !!}
                <div class="text-center">{!! $tableData->appends(Input::except('page'))->links() !!}</div>
            @else
                <p class="text-muted">Nenhum registro encontrado</p>
            @endif
        </div>
    </div>
@stop