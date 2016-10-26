@extends('layouts.modulos.seguranca')

@section('title')
    Usuários
@stop

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('/css/plugins/datepicker3.css') }}">
@stop

@section('subtitle')
    Cadastro de usuários
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de Cadastro de Usuários</h3>
        </div>
        <div class="box-body">
            {!! Form::open(["url" => url('/') . "/seguranca/usuarios/create", "method" => "POST", "id" => "form", "role" => "form"]) !!}
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
    <script src="{{ asset('/js/plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{asset('/js/plugins/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('/js/plugins/bootstrap-datepicker.pt-BR.js')}}"></script>
    <script src="{{ asset('/js/plugins/cpfcnpj.min.js') }}"></script>

    <script>

        $(function (){
            $('#doc_conteudo').inputmask({"mask": "999.999.999-99", "removeMaskOnSubmit": true});
            $('#pes_telefone').inputmask({"mask": "(99) 99999-9999", "removeMaskOnSubmit": true});
            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy',
                language: 'pt-BR',
                autoclose: true
            });
        });
    </script>
@endsection