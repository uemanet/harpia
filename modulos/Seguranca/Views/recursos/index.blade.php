@extends('layouts.interno')

@section('stylesheets')
    {!! Html::style('/assets/vendor/sweetalert/sweetalert.css') !!}
@stop

@section('title')
    <div class="row">
        <div class="col-md-8">
            <h2 style="margin:0px;font-size:30px" class="lead" >Recursos</h2>
        </div>
        @haspermission('security/recursos/create')
        <div class="col-md-4">
            <a href="{{ url('/') }}/security/recursos/create" class="pull-right btn btn-lg btn-info" title="Novo Recurso"><i class="fa fa-plus"></i> Novo Recurso</a>
        </div>
        @endhaspermission
    </div>
@stop

@section('content')
    @if($recursos->count())
        <div class="panel panel-default ">
            <div class="panel-body">
                <div class="ibox float-e-margins wrapper wrapper-content">
                    <div class="ibox-content">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Módulo</th>
                                <th class="hidden-xs hidden-sm">Categoria</th>
                                <th>Nome</th>
                                <th>Descrição</th>
                                <th>Icone</th>
                                <th>Ativo</th>
                                <th>Ordem</th>
                                <th style="width: 70px;">Opções</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($recursos as $recurso)
                                <tr>
                                    <td>{{ $recurso->modulo->mod_nome }}</td>
                                    <td class="hidden-xs hidden-sm">{{ $recurso->categoria->ctr_nome }}</td>
                                    <td>{{ $recurso->rcs_nome }}</td>
                                    <td>{{ $recurso->rcs_descricao }}</td>
                                    <td>{{ $recurso->rcs_icone }}</td>
                                    <td>{{ $recurso->rcs_ativo }}</td>
                                    <td>{{ $recurso->rcs_ordem }}</td>
                                    <td class="text-center">
                                        @haspermission('security/recursos/edit')
                                        <a style="float:left;margin-right:5px" href="{{ url('/') }}/security/recursos/edit/{{$recurso->rcs_id}}" class="btn btn-warning btn-xs btn-alterar"><i class="icon-pencil"></i></a>
                                        @endhaspermission
                                        @haspermission('security/recursos/delete')
                                        <form action="{{ url('/') }}/security/recursos/delete" method="POST">
                                            <input type="hidden" name="_token" value="{{csrf_token()}}" />
                                            <input type="hidden" name="rcs_id" value="{{$recurso->rcs_id}}" />
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
                title: "Deletar Recurso?",
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