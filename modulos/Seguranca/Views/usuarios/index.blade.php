@extends('layouts.modulos.seguranca')

@section('title')
    Usuários
@stop

@section('subtitle')
    Gerenciamento de usuários
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
                <form method="GET" action="{{ url('/seguranca/usuarios/index') }}">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="pes_cpf" id="pes_cpf" value="{{Input::get('pes_cpf')}}" placeholder="CPF">
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="pes_nome" id="pes_nome" value="{{Input::get('pes_nome')}}" placeholder="Nome">
                    </div>
                    <div class="col-md-3">
                        <input type="email" class="form-control" name="pes_email" id="pes_email" value="{{Input::get('pes_email')}}" placeholder="Email">
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
                        'pes_id' => '#',
                        'pes_nome' => 'Nome',
                        'pes_email' => 'Email',
                        'doc_conteudo' => 'CPF',
                        'pes_action' => 'Ações'
                    ))
                    ->modifyCell('pes_action', function() {
                        return array('style' => 'width: 140px;');
                    })
                    ->means('pes_action', 'pes_id')
                    ->modify('pes_action', function($id) {
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
                                        'action' => '/seguranca/usuarios/edit/' . $id,
                                        'label' => 'Editar',
                                        'method' => 'get'
                                    ],
                                    [
                                        'classButton' => 'btn-delete text-red',
                                        'icon' => 'fa fa-trash',
                                        'action' => '/seguranca/usuarios/delete',
                                        'id' => $id,
                                        'label' => 'Excluir',
                                        'method' => 'post'
                                    ]
                                ]
                            ]);
                    })
                    ->sortable(array('pes_id', 'pes_nome'))
                    ->render()
                !!}
            </div>
        </div>

        <div class="text-center">{!! $tableData->appends(Input::except('page'))->links() !!}</div>
    @endif
@stop