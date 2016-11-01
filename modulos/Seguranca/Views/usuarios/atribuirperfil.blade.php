@extends('layouts.modulos.seguranca')

@section('title')
    Atribuir Perfis
@stop

@section('subtitle')
    <b>Usuario:</b> {{ $usuario->pessoa->pes_nome }}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">Atribuir Novo Perfil</h3>
        </div>
        <div class="box-body">
            <div class="row">
                {!! Form::open(array('route' => ['seguranca.usuarios.postAtribuirperfil', $usuario->usr_id], 'method' => 'POST', 'id' => 'formAtribuirPerfil')) !!}
                    <div class="form-group col-md-3">
                        @if(!empty($modulos))
                            {!! Form::select('mod_id', $modulos, old('mod_id'), ['class' => 'form-control', 'id' => 'mod_id', 'placeholder' => 'Selecione o módulo']) !!}
                        @else
                            {!! Form::select('mod_id', [], null, ['class' => 'form-control', 'id' => 'mod_id', 'placeholder' => 'Sem módulos']) !!}
                        @endif
                    </div>
                    <div class="form-group col-md-3">
                        <div class="controls">
                            {!! Form::select('prf_id', [], null, ['class' => 'form-control','id' => 'prf_id']) !!}
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        {!! Form::submit('Atribuir', ['class' => 'btn btn-primary', 'id' => 'btnAtribuir']) !!}
                    </div>
                {!! Form::close() !!}
            </div>
            <div class="row">
                <div class="col-md-12">
                @if(count($usuario->perfis))
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <th style="width: 10px">#</th>
                            <th>Módulo</th>
                            <th>Perfil</th>
                            <th>Descrição</th>
                            <th style="width: 20px"></th>
                        </thead>
                        <tbody>
                            @foreach($usuario->perfis as $perfil)
                                <tr>
                                    <td>{{$perfil->prf_id}}</td>
                                    <td>{{$perfil->modulo->mod_nome}}</td>
                                    <td>{{$perfil->prf_nome}}</td>
                                    <td>{{$perfil->prf_descricao}}</td>
                                    <td>
                                        {!! ActionButton::grid([
                                            'type' => 'LINE',
                                            'buttons' => [
                                                [
                                                    'classButton' => 'btn btn-danger btn-delete',
                                                    'icon' => 'fa fa-trash',
                                                    'action' => '/seguranca/usuarios/deletarperfil/'.$usuario->usr_id,
                                                    'id' => $perfil->prf_id,
                                                    'label' => '',
                                                    'method' => 'post'
                                                ]
                                            ]
                                        ]) !!}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>Sem perfis associados ao usuario</p>
                @endif
                </div>    
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script type="application/javascript">
        $(function() {

           $('#mod_id').change(function (e) {
               var moduloId = $(this).val();

               if(!moduloId)
               {
                   return false;
               }

               $.harpia.httpget('{{url('/')}}/seguranca/async/perfis/findallbymodulo/' + moduloId).done(function (data) {
                   $('#prf_id').empty();
                   if($.isEmptyObject(data)) {
                       $('#prf_id').append("<option value='' selected>Sem perfis associados</option>");
                   } else {
                       $('#prf_id').append("<option value='' selected>Selecione um perfil</option>");
                       $.each(data, function (key, value) {

                           $('#prf_id').append("<option value=" + value.prf_id + " >" + value.prf_nome + "</option>");
                       });

                   }
               });
           });

            $('#btnAtribuir').click(function (e) {
                e.preventDefault();

                var modulo = $('#mod_id').val();
                var perfil = $('#prf_id').val();

                if(modulo == '' || perfil == '') {
                    return false;
                }

                $('#formAtribuirPerfil').submit();
            })
        });
    </script>
@stop