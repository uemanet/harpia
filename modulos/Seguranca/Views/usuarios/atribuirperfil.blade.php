@extends('layouts.modulos.default')

@section('title')
    Atribuir Perfis
@stop

@section('subtitle')
    <b>Usuario:</b> {{ $usuario->pessoa->pes_nome }}
@stop

@section('content')
    <div class="row">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title m-0">Atribuir Novo Perfil</h3>
            </div>
            <div class="card-body">
                <div class="row py-2">
                    {!! Form::open(array('route' => ['seguranca.usuarios.atribuirperfil', $usuario->usr_id], 'method' => 'POST', 'id' => 'formAtribuirPerfil', 'class' => 'w-100 d-flex')) !!}
                    <div class="form-group col-md-5 px-1">
                        @if(!empty($modulos))
                            {!! Form::select('mod_id', $modulos, old('mod_id'), ['class' => 'form-control', 'id' => 'mod_id', 'placeholder' => 'Selecione o módulo']) !!}
                        @else
                            {!! Form::select('mod_id', [], null, ['class' => 'form-control', 'id' => 'mod_id', 'placeholder' => 'Sem módulos']) !!}
                        @endif
                    </div>
                    <div class="form-group col-md-5 px-1">
                        <div class="form-group">
                            {!! Form::select('prf_id', [], null, ['class' => 'form-control','id' => 'prf_id']) !!}
                        </div>
                    </div>
                    <div class="form-group col-md-2 px-1 text-center">
                        {!! Form::submit('Atribuir', ['class' => 'btn btn-primary w-100', 'id' => 'btnAtribuir']) !!}
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
                                                        'route' => 'seguranca.usuarios.deletarperfil',
                                                        'parameters' => ['id' => $usuario->usr_id],
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