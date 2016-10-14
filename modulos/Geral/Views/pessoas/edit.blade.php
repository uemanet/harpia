@extends('layouts.modulos.geral')

@section('title')
    Pessoas
@stop

@section('subtitle')
    Alterar pessoa :: {{$pessoa->pes_nome}}
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Formulário de edição de pessoa</h3>
        </div>
        <div class="box-body">
            {!! Form::model($pessoa,["url" => url('/') . "/geral/pessoas/edit/$pessoa->pes_id", "method" => "PUT", "id" => "form", "role" => "form"]) !!}
                @include('Geral::pessoas.includes.formulario')

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
            Inputmask({"mask": "999.999.999-99", "removeMaskOnSubmit": true}).mask('#pes_cpf');
            Inputmask({"mask": "(99) 99999-9999"}).mask('#pes_telefone');
        });
    </script>
@endsection
