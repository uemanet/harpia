@extends('layouts.interno')

@section('stylesheets')
    {!! Html::style('/assets/vendor/sweetalert/sweetalert.css') !!}

    <style type="text/css">
        .btnAtribuir{
            margin-top: 30px;
        }
    </style>
@stop

@section('title')
    <div class="row">
        <div class="col-md-8">
            <h2 style="margin:0px;font-size:30px" class="lead" >Atribuir Perfil ao Usuário</h2>
        </div>
    </div>
@stop

@section('content')
    <div class="panel panel-default ">
        <div class="panel-heading panel-heading-collapsed">
            <h3 style="margin:0px;font-size:30px" class="lead">Usuário: {{ $usuario->usr_nome }}</h3>
        </div>
        <div class="panel-body">
            <div class="ibox float-e-margins wrapper wrapper-content">
                <div class="ibox-content">
                    <div class="ibox">
                        <div class="ibox-content">
                            <div class="row atribuir">
                                {!! Form::open(["url" => "/security/perfisusuarios/atribuir", "method" => "POST", "id" => "form", "role" => "form"]) !!}
                                    <div class="form-horizontal col-md-3">
                                        {!! Form::label('modulo', 'Escolha um Módulo:') !!}
                                        <input type="hidden" name="usr_id" value="{{$usuario->usr_id}}" />
                                        @if(isset($modulosNaoVinculados))
                                            {!! Form::select('modulo', $modulosNaoVinculados, null, ['class' => 'form-control select input-lg']) !!}
                                        @else
                                            {!! Form::select('modulo', array(), null, ['class' => 'form-control select status input-lg']) !!}
                                        @endif
                                    </div>
                                {!! Form::close() !!}
                            </div>
                            <br />
                            
                            @if(count($perfis))
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Módulo</th>
                                        <th>Perfil</th>
                                        <th>Descrição do Perfil</th>
                                        <th style="width: 10px;"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($perfis as $perfil)
                                        <tr>
                                            <td>{{ $perfil->mod_nome }}</td>
                                            <td>{{ $perfil->prf_nome }}</td>
                                            <td>{{ $perfil->prf_descricao }}</td>
                                            <td class="text-center">
                                                <form action="{{ url('/') }}/security/perfisusuarios/desvincularperfil" method="POST">
                                                    <input type="hidden" name="_token" value="{{csrf_token()}}" />
                                                    <input type="hidden" name="usr_id" value="{{$usuario->usr_id}}" />
                                                    <input type="hidden" name="prf_id" value="{{$perfil->prf_id}}" />
                                                    <button type="submit" aria-label="Desvincular perfil {{ $perfil->prf_nome }}" class="btn btn-danger btn-xs btn-excluir"><i class="fa fa-remove"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
    <script src="{{ asset('/assets/vendor/sweetalert/sweetalert.min.js') }}"></script>

    <script type="text/javascript">
        $(".btn-excluir").on("click" , function(e){
            $this = $(this);

            swal({
                title: "Remover permissão?",
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

        var baseUrl = "{{ url('/') }}";

        $( "#modulo" ).change(function() {
            if($(this).val() == 0){
                $( ".divPerfis" ).remove();
                $( ".btnAtribuir" ).remove();
            } else {
                var urlPerfisAjax = baseUrl + '/security/perfisusuarios/perfis?id=' + $( "#modulo" ).val();
                $.getJSON( urlPerfisAjax, function( data ) {
                    $( ".divPerfis" ).remove();
                    $( ".btnAtribuir" ).remove();
                    $( "<div class='form-horizontal col-md-3 divPerfis'>" ).appendTo( $( "#form" ) );
                    $( "<label>Escolha um Perfil:</label>" ).appendTo( $( ".divPerfis" ) );
                    $( "<select class='form-control select input-lg perfis' name='prf_id'>" ).appendTo( $( ".divPerfis" ) );
                    $( "<div class='form-horizontal col-md-2 divPerfis'><button type='submit' class='btn btn-primary  btnAtribuir'>Atribuir Perfil</button>" ).appendTo( $( "#form" ) );
                    var id;
                    for (id in data) {
                        $( "<option value="+ id +">"+data[id] +"</option>" ).appendTo( $( ".perfis" ) );
                    }
                    console.log( data.length );
                })
            }
        });
    </script>
@stop