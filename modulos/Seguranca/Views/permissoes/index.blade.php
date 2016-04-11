@extends('layouts.interno')

@section('stylesheets')
    {!! Html::style('/assets/vendor/sweetalert/sweetalert.css') !!}
@stop

@section('title')
    <div class="row">
        <div class="col-md-8">
            <h2 style="margin:0px;font-size:30px" class="lead" >Permissões</h2>
        </div>
        @haspermission('security/permissoes/create')
        <div class="col-md-4">
            <a href="{{ url('/') }}/security/permissoes/create" class="pull-right btn btn-lg btn-info" title="Nova permissão"><i class="fa fa-plus"></i> Nova Permissão</a>
        </div>
        @endhaspermission()
    </div>
@stop

@section('content')
    @if($permissoes->count())
        <div class="panel panel-default ">
            <div class="panel-body">
                <div class="ibox float-e-margins wrapper wrapper-content">
                    <div class="ibox-content">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Modulo</th>
                                <th>Recurso</th>
                                <th>Permissão</th>
                                <th>Descricao</th>
                                <th style="width: 70px;"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($permissoes as $permissao)
                                <tr>
                                    <td>{{ $permissao->recurso->modulo->mod_nome }}</td>
                                    <td>{{ $permissao->recurso->rcs_nome }}</td>
                                    <td>{{ $permissao->prm_nome }}</td>
                                    <td>{{ $permissao->prm_descricao }}</td>
                                    <td class="text-center">
                                        @haspermission('security/permissoes/edit')
                                        <a style="float:left;margin-right:5px" href="{{ url('/') }}/security/permissoes/edit/{{$permissao->prm_id}}" class="btn btn-warning btn-xs btn-alterar"><i class="icon-pencil"></i></a>
                                        @endhaspermission()
                                        @haspermission('security/permissoes/delete')
                                        <form action="{{ url('/') }}/security/permissoes/delete" method="POST">
                                            <input type="hidden" name="_token" value="{{csrf_token()}}" />
                                            <input type="hidden" name="prm_id" value="{{$permissao->prm_id}}" />
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
                title: "Deletar Permissão?",
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