@extends('layouts.modulos.default')

@section('title')
    Colaboradores
@stop

@section('scripts')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            var inputFoto = document.getElementById('col_foto_facial');
            var previewWrapper = document.getElementById('preview-foto-facial-wrapper');
            var previewImagem = document.getElementById('preview-foto-facial');

            if (!inputFoto || !previewWrapper || !previewImagem) {
                return;
            }

            inputFoto.addEventListener('change', function (event) {
                var arquivo = event.target.files && event.target.files[0] ? event.target.files[0] : null;

                if (!arquivo) {
                    return;
                }

                if (!arquivo.type.match(/^image\//)) {
                    return;
                }

                var leitor = new FileReader();

                leitor.onload = function (loadEvent) {
                    previewImagem.src = loadEvent.target.result;
                    previewWrapper.style.display = 'block';
                };

                leitor.readAsDataURL(arquivo);
            });
        });
    </script>
@endsection

@section('subtitle')
    Alterar Colaborador :: {{$pessoa->pes_nome}}
@stop

@section('content')
    <div class="card card-success card-outline">
        <div class="card-header with-border">
            <h3 class="card-title">Formulário de Edição de Colaborador</h3>
        </div>
        <form action="{{ route('rh.colaboradores.edit', [$colaborador->col_id]) }}" method="POST" id="form" role="form">
            @csrf
            @method('PUT')
            <div class="card-body">
                @include('Geral::pessoas.includes.formulario', ['pessoa' => $pessoa])
                @include('RH::colaboradores.includes.formulario_edit', ['colaborador' => $colaborador])
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" style="float: right">Salvar Dados</button>
            </div>
        </form>
    </div>
@stop