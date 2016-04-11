@extends('layouts.interno')

@section('stylesheets')
    {!! Html::style('/assets/vendor/sweetalert/sweetalert.css') !!}
@stop

@section('title')
    <div class="row">
        <div class="col-md-8">
            <h2 style="margin:0px;font-size:30px" class="lead" >Módulos</h2>
        </div>
        
        @haspermission('security/modulos/create')
            <div class="col-md-4">
                <a href="{{ url('/') }}/security/modulos/create" class="pull-right btn btn-lg btn-info" title="Novo módulo"><i class="fa fa-plus"></i> Novo Módulo</a>
            </div>
        @endhaspermission
    </div>
@stop

@section('content')
    @if($modulos->count())
        <div class="panel panel-default ">
            <div class="panel-body">
                <div class="ibox float-e-margins wrapper wrapper-content">
                    <div class="ibox-content">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Data do módulo</th>
                                <th>Descrição do módulo</th>
                                <th style="width: 70px;"></th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($modulos as $modulo)
                                    <tr>
                                        <td>{{ $modulo->mod_id }}</td>
                                        <td>{{ $modulo->mod_nome }}</td>
                                        <td>{{ $modulo->mod_descricao }}</td>
                                        <td class="text-center">
                                            @haspermission('security/modulos/edit')
                                                <a style="float:left;margin-right:5px" href="{{ url('/') }}/security/modulos/edit/{{$modulo->mod_id}}" class="btn btn-warning btn-xs btn-alterar"><i class="icon-pencil"></i></a>
                                            @endhaspermission
                                            @haspermission('security/modulos/delete')
                                                <form action="{{ url('/') }}/security/modulos/delete" method="POST">
                                                    <input type="hidden" name="_token" value="{{csrf_token()}}" />
                                                    <input type="hidden" name="mod_id" value="{{$modulo->mod_id}}" />
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