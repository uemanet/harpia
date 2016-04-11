@extends('layouts.interno')

@section('stylesheets')
    {!! Html::style('/assets/vendor/sweetalert/sweetalert.css') !!}
@stop

@section('title')
    <div class="row">
        <div class="col-md-8">
            <h2 style="margin:0px;font-size:30px" class="lead" >Perfis Usuários</h2>
        </div>
    </div>
@stop

@section('content')
    @if(count($usuarios))
        <div class="panel panel-default ">
            <div class="panel-body">
                <div class="ibox float-e-margins wrapper wrapper-content">
                    <div class="ibox-content">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>Usuário</th>
                                <th style="width: 120px;"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($usuarios as $usuario)
                                <tr>
                                    <td>{{ $usuario->usr_id }}</td>
                                    <td>{{ $usuario->usr_nome }}</td>
                                    @haspermission('security/perfisusuarios/atribuirperfis')
                                    <td><a class="btn btn-info" href="{{ url() }}/security/perfisusuarios/atribuirperfis/{{$usuario->usr_id}}"><i class="fa fa-check-circle"></i> Atribuir perfil</a></a></td>
                                    @endhaspermission
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
@stop