@extends('layouts.modulos.seguranca')

@section('title')
    Atribuir permissões
@stop

@section('subtitle')
    <b>Usuario:</b> {{ $usuario->pessoa->pes_nome }}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header"></div>
        <div class="box-body">
            {!! Form::open(['route' => ['seguranca.usuarios.postAtribuirperfil', $usuario->usr_id], 'method' => 'POST', 'role' => 'form']) !!}
                <div class="form-group">
                    @foreach($perfis as $perfil)
                        <div class="checkbox">
                            <label>
                                {!! Form::checkbox('perfis', $perfil->prf_id) !!}
                                <strong>Perfil:</strong> {{$perfil->prf_nome}} <strong>Módulo:</strong> {{$perfil->modulo->mod_nome}}
                            </label>
                        </div>
                    @endforeach
                </div>
                    {!! Form::submit('Atribuir perfis', ['class' => 'btn btn-success']) !!}
            {!! Form::close() !!}
        </div>
    </div>
@stop