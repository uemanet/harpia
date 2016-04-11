@extends('layouts.interno')

@section('stylesheets')
    {!! Html::style('/assets/vendor/sweetalert/sweetalert.css') !!}
@stop

@section('title')
    <div class="row">
        <div class="col-md-8">
            <h2 style="margin:0px;font-size:30px" class="lead" >Categorias de Recursos</h2>
        </div>
        @haspermission('security/modulos/create')
        <div class="col-md-4">
            <a href="{{ url('/') }}/security/categoriasrecursos/create" class="pull-right btn btn-lg btn-info" title="Nova categoria"><i class="fa fa-plus"></i> Nova Categoria</a>
        </div>
        @endhaspermission
    </div>
@stop

@section('content')
    @if($categoriasrecursos->count())
        <div class="panel panel-default ">
            <div class="panel-body">
                <div class="ibox float-e-margins wrapper wrapper-content">
                    <div class="ibox-content">    
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Icone</th>
                                <th>Ordem</th>
                                <th>Ativo</th>
                                <th style="width: 70px;">Opções</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($categoriasrecursos as $categoria)
                                    <tr>
                                        <td>{{ $categoria->ctr_id }}</td>
                                        <td>{{ $categoria->ctr_nome }}</td>
                                        <td>{{ $categoria->ctr_icone }}</td>
                                        <td>{{ $categoria->ctr_ordem }}</td>
                                        <td>{{ ($categoria->ctr_ativo == 1 ? 'Sim':'Não') }}</td>
                                        @haspermission('security/modulos/edit')
                                        <td class="text-center">
                                            <a style="float:left;margin-right:5px" href="{{ url('/') }}/security/categoriasrecursos/edit/{{$categoria->ctr_id}}" class="btn btn-warning btn-xs btn-alterar"><i class="icon-pencil"></i></a>
                                        @endhaspermission
                                        @haspermission('security/modulos/delete')
                                            <form action="{{ url('/') }}/security/categoriasrecursos/delete" method="POST">
                                                <input type="hidden" name="_token" value="{{csrf_token()}}" />
                                                <input type="hidden" name="ctr_id" value="{{$categoria->ctr_id}}" />
                                                <button type="submit" aria-label="Excluir registro?" class="btn btn-danger btn-xs btn-excluir"><i class="fa fa-remove"></i></button>
                                            </form>
                                        </td>
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

@section('scripts')
    <script src="{{ asset('/assets/vendor/sweetalert/sweetalert.min.js') }}"></script>

    <script type="text/javascript">
        $(".btn-excluir").on("click" , function(e){
            $this = $(this);

            swal({
                title: "Deletar Módulo?",
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