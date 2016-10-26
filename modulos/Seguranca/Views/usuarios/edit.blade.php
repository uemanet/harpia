@extends('layouts.modulos.seguranca')

@section('title')
    Usuários
@stop

@section('subtitle')
    Alterar usuario :: {{$usuario->usr_usuario}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de usuário</h3>
        </div>
        <div class="box-body">
            {!! Form::model($usuario,['route' => ['seguranca.usuarios.putEdit', $usuario->usr_id], "method" => "PUT", "id" => "form", "role" => "form"]) !!}
            <h4 class="box-title">
                Dados de Usuário
            </h4>
            @include('Seguranca::usuarios.includes.formulario')

            <hr>
            <h4 class="box-title">
                Dados de Pessoa
            </h4>
            @include('Geral::pessoas.includes.formulario', ['pessoa' => $pessoa])

            <div class="row">
                <div class="form-group col-md-12">
                    {!! Form::submit('Salvar dados', ['class' => 'btn btn-primary pull-right']) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{ asset('/js/plugins/input-mask/inputmask.js') }}"></script>
    <script src="{{ asset('/js/plugins/input-mask/inputmask.extensions.js') }}"></script>
    <script src="{{ asset('/js/plugins/input-mask/date.extensions.js') }}"></script>
    <script src="{{ asset('/js/plugins/cpfcnpj.min.js') }}"></script>

    <script>

        $(function (){
            $('.datepicker').datepicker({
                format: "dd/mm/yyyy",
                language: 'pt-BR',
                autoclose: true
            });
            $('#doc_conteudo').inputmask({"mask": "999.999.999-99", "removeMaskOnSubmit": true});
            $('#pes_telefone').inputmask({"mask": "(99) 99999-9999", "removeMaskOnSubmit": true});
        });
    </script>
@endsection
