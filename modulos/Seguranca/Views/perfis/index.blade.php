@extends('layouts.interno')

@section('stylesheets')
    {!! Html::style('/assets/vendor/sweetalert/sweetalert.css') !!}
@stop

@section('title')
    <div class="row">
        <div class="col-md-8">
            <h2 style="margin:0px;font-size:30px" class="lead" >Perfis</h2>
        </div>
        @haspermission('security/perfis/create')
        <div class="col-md-4">
            <a href="{{ url('/') }}/security/perfis/create" class="pull-right btn btn-lg btn-info" title="Novo perfil"><i class="fa fa-plus"></i> Novo Perfil</a>
        </div>
        @endhaspermission()
    </div>
@stop

@section('content')
    @if($perfis->count())
        <div class="panel panel-default ">
            <div class="panel-body">
                <div class="ibox float-e-margins wrapper wrapper-content">
                    <div class="ibox-content">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Módulo</th>
                                <th>Perfil</th>
                                <th>Descrição</th>
                                <th style="width: 70px;">Opções</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($perfis as $perfil)
                                <tr>
                                    <td>{{ $perfil->modulo->mod_descricao }}</td>
                                    <td>{{ $perfil->prf_nome }}</td>
                                    <td>{{ $perfil->prf_descricao }}</td>
                                    <td class="text-center">
                                        @haspermission('security/perfis/edit')
                                        <a style="float:left;margin-right:5px" href="{{ url('/') }}/security/perfis/edit/{{$perfil->prf_id}}" class="btn btn-warning btn-xs btn-alterar"><i class="icon-pencil"></i></a>
                                        @endhaspermission()
                                        @haspermission('security/perfis/delete')
                                        <form action="{{ url('/') }}/security/perfis/delete" method="POST">
                                            <input type="hidden" name="_token" value="{{csrf_token()}}" />
                                            <input type="hidden" name="prf_id" value="{{$perfil->prf_id}}" />
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
                title: "Deletar Perfil?",
                text: "Você tem certeza que deseja deletar esse registro?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Sim, desejo deletar!",
                closeOnConfirm: false
            }, function(){
                $this.closest('form').submit();
            });
            
            e.preventDefault();
        });
    </script>
@stop