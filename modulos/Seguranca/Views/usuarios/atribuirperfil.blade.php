@extends('layouts.modulos.seguranca')

@section('title')
    Atribuir Perfis
@stop

@section('stylesheets')
    <link href="{{ asset('/css/plugins/jstree/style.min.css') }}" rel="stylesheet"/>
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
                {!! Form::open(array('route' => ['seguranca.usuarios.postAtribuirperfil', $usuario->usr_id], 'method' => 'POST')) !!}   
                    <div class="col-md-3">                         
                        <div class="form-group">
                            {!! Form::select('mod_id', $modulos, '', ['class' => 'form-control', 'placeholder' => 'Selecione o módulo']) !!}                            
                        </div>                      
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::select('prf_id', [], '', ['class' => 'form-control', 'placeholder' => 'Selecione o perfil']) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::submit('Atribuir', ['class' => 'btn btn-primary']) !!}
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
            <div class="row">
                <div class="col-md-12">
                @if(count($usuario->perfis))
                    <table class="table table-bordered">
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
                                    <td><a href="" class="btn btn-danger"><i class="fa fa-trash"></i></a></td>
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
    <script type="text/javascript">
        
    </script>
@stop