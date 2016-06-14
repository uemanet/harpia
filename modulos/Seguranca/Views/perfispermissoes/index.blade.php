@extends('layouts.interno')

@section('title')
    Perfis Permissões
@stop

@section('subtitle')
    Atribuir permissão a um perfil
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-filter"></i> Filtrar dados</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <form method="GET" action="{{ url('/seguranca/perfis/index') }}">
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="prf_nome" id="prf_nome" value="{{Input::get('prf_nome')}}" placeholder="Nome do perfil">
                    </div>
                    <div class="col-md-3">
                        <input type="submit" class="form-control btn-primary" value="Buscar">
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if($tableData->count())
        <div class="box box-primary">
            <div class="box-header">
                {!!
                    $tableData->columns(array(
                        'prf_id' => '#',
                        'prf_nome' => 'Perfil',
                        'prf_descricao' => 'Descrição',
                        'prf_action' => 'Ações'
                    ))
                    ->modifyCell('prf_action', function() {
                        return array('style' => 'width: 140px;');
                    })
                    ->means('prf_action', 'prf_id')
                    ->modify('prf_action', function($id) {
                        return ActionButton::grid([
                                'type' => 'BUTTONS',
                                'config' => [
                                    'showLabel' => true
                                ],
                                'buttons' => [
                                    [
                                        'classButton' => 'btn-info',
                                        'icon' => 'fa fa-plus',
                                        'action' => '/seguranca/perfispermissoes/atribuirpermissoes/'.$id,
                                        'label' => 'Atribuir Permissões',
                                        'target' => ''
                                    ]
                                ]
                            ]);
                    })
                    ->sortable(array('prf_id', 'prf_nome'))
                    ->render()
                !!}
            </div>
        </div>

        <div class="text-center">{!! $tableData->appends(Input::except('page'))->links() !!}</div>
    @endif
@stop