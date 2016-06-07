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
  			{!!MasterMenu::render()!!}
        </div>
    </div>
@stop