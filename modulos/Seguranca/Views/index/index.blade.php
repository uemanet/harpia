@extends('layouts.interno')

@section('title')
    Módulo de Segurança
@stop

@section('subtitle')
    Módulo de Segurança
@stop

@section('actionButton')
	{!!ActionButton::render($actionButton)!!}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header">

          {!!
            $modulos->columns(array(
                'mod_id' => 'ID',
                'mod_nome' => 'Modulo',
                'action' => '#'
            ))
            ->modify('action', function(){
                return ActionButton::grid(
                        [
                          'type' => 'BUTTONS',
                          'config' => [
                            'showLabel' => true
                          ],
                          'buttons' => [
                            [
                              'classButton' => 'btn-default',
                              'icon' => 'fa fa-plus',
                              'action' => 'seguranca/index',
                              'label' => 'Seguranca',
                              'target' => ''
                            ],
                            [
                              'classButton' => '',
                              'icon' => 'fa fa-circle-o',
                              'action' => 'securanca/show',
                              'label' => 'Visualizar',
                              'target' => ''
                            ],
                          ]
                        ]
                      );
            })
            ->render() 
        !!}


        <br><br><br><br>
  			{!!ActionButton::grid(
  				[
  					'type' => 'SELECT',
  					'config' => [
  						'classButton' => 'btn-default',
  						'label' => 'Selecione'
  					],
  					'buttons' => [
  						[
	  						'classButton' => 'btn-default',
	  						'icon' => 'fa fa-plus',
	  						'action' => 'seguranca/index',
	  						'label' => 'Seguranca',
	  						'target' => ''
  						],
  						[
	  						'classButton' => '',
	  						'icon' => 'fa fa-circle-o',
	  						'action' => 'securanca/show',
	  						'label' => 'Visualizar',
	  						'target' => ''
  						],
  					]
  				]
  			)!!}
	
  			<br><br>

  			{!!ActionButton::grid(
  				[
  					'type' => 'BUTTONS',
  					'config' => [
  						'showLabel' => false
  					],
  					'buttons' => [
  						[
	  						'classButton' => 'btn-danger',
	  						'icon' => 'fa fa-plus',
	  						'action' => 'seguranca/index',
	  						'label' => 'Excluir',
	  						'target' => ''
  						],
  						[
	  						'classButton' => 'btn-info',
	  						'icon' => 'fa fa-circle-o',
	  						'action' => 'securanca/show',
	  						'label' => 'Show',
	  						'target' => ''
  						],
  					]
  				]
  			)!!}

  			<br><br>

  			{!!ActionButton::grid(
  				[
  					'type' => 'BUTTONS',
  					'config' => [
  						'showLabel' => true
  					],
  					'buttons' => [
  						[
	  						'classButton' => 'btn-danger',
	  						'icon' => 'fa fa-plus',
	  						'action' => 'seguranca/index',
	  						'label' => 'Excluir',
	  						'target' => ''
  						],
  						[
	  						'classButton' => 'btn-info',
	  						'icon' => 'fa fa-circle-o',
	  						'action' => 'securanca/show',
	  						'label' => 'Show',
	  						'target' => ''
  						],
  					]
  				]
  			)!!}
        </div>
    </div>

  
@stop