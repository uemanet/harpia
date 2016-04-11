@extends('layouts.interno')

@section('title')
    <div class="row">
        <div class="col-md-8">
            <h2 style="margin:0px;font-size:30px" class="lead" >Perfis Permissões</h2>
        </div>
    </div>
@stop

@section('content')
    @if(count($perfis))
        <div class="panel panel-default ">
            <div class="panel-body">
                <div class="ibox float-e-margins wrapper wrapper-content">
                    <div class="ibox-content">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Módulo</th>
                                <th>Perfil</th>
                                <th>Descrição do perfil</th>
                                <th style="width: 120px;">Opções</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($perfis as $perfil)
                                <tr>
                                    <td>{{ $perfil->prf_id }}</td>
                                    <td>{{ $perfil->mod_nome }}</td>
                                    <td>{{ $perfil->prf_nome }}</td>
                                    <td>{{ $perfil->prf_descricao }}</td>
                                    <td><a class="btn btn-info" href="{{ url() }}/security/perfispermissoes/atribuirpermissoes/{{$perfil->prf_id}}"><i class="fa fa-check-circle"></i> Atribuir permissões</a></a></td>
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