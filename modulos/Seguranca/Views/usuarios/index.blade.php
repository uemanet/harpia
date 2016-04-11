@extends('layouts.interno')

@section('stylesheets')
    {!! Html::style('/assets/vendor/sweetalert/sweetalert.css') !!}
@stop

@section('title')
    <div class="row">
        <div class="col-md-8">
            <h2 style="margin:0px;font-size:30px" class="lead" >Usuários</h2>
        </div>
        @haspermission('security/usuarios/create')
        <div class="col-md-4">
            <a href="{{ url('/') }}/security/usuarios/create" class="pull-right btn btn-lg btn-info" title="Novo Usuário"><i class="fa fa-plus"></i> Novo Usuário</a>
        </div>
        @endhaspermission
    </div>
@stop

@section('content')
    @if($usuarios->count())
        <div class="panel panel-default ">
            <div class="panel-body">
                <div class="ibox float-e-margins wrapper wrapper-content">
                    <div class="ibox-content">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Telefone</th>
                                <th>Usuario</th>
                                <th>Ativo</th>
                                <th style="width: 70px;">Opções</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($usuarios as $usuario)
                                <tr>
                                    <td>{{ $usuario->usr_nome }}</td>
                                    <td>{{ $usuario->usr_email }}</td>
                                    <td>{{ $usuario->usr_telefone }}</td>
                                    <td>{{ $usuario->usr_usuario }}</td>
                                    <td>{{ $usuario->usr_ativo }}</td>
                                    <td class="text-center">
                                        @haspermission('security/usuarios/edit')
                                        <a style="float:left;margin-right:5px" href="{{ url('/') }}/security/usuarios/edit/{{$usuario->usr_id}}" class="btn btn-warning btn-xs btn-alterar"><i class="icon-pencil"></i></a>
                                        @endhaspermission
                                        @haspermission('security/usuarios/delete')
                                        <form action="{{ url('/') }}/security/usuarios/delete" method="POST">
                                            <input type="hidden" name="_token" value="{{csrf_token()}}" />
                                            <input type="hidden" name="usr_id" value="{{$usuario->usr_id}}" />
                                            <button type="submit" aria-label="Excluir registro?" class="btn btn-danger btn-xs btn-excluir"><i class="fa fa-remove"></i></button>
                                        </form>
                                        @endhaspermission
                                    </td>
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

@section('scripts')
    <script src="{{ asset('/assets/vendor/sweetalert/sweetalert.min.js') }}"></script>

    <script type="text/javascript">
        $(".btn-excluir").on("click" , function(e){
            $this = $(this);

            swal({
                title: "Deletar Usuário?",
                text: "Você tem certeza que deseja deletar esse registro?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Sim, desejo deletar!",
                cancelButtonText: "Cancelar",
                closeOnConfirm: false
            }, function(){
                $this.closest('form').submit();
            });
            
            e.preventDefault();
        });
    </script>
@stop