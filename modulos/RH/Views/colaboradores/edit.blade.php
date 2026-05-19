@extends('layouts.modulos.default')

@section('title')
    Colaboradors
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
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title m-0">Formulário de Edição de Colaborador</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('rh.colaboradores.edit', [$colaborador->col_id]) }}" method="POST" id="form" role="form" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    {{-- Form model: $pessoa - inputs devem usar old('campo', $pessoa->campo) --}}

            <h4 class="card-title m-0">
                Dados de Pessoa
            </h4>
            @include('Geral::pessoas.includes.formulario', ['pessoa' => $pessoa])

            <h4 class="card-title m-0">
                Dados do colaborador
            </h4>
            @include('RH::colaboradores.includes.formulario_edit', ['colaborador' => $colaborador])

            <div class="row">
                <div class="form-group col-md-12">
                    <button type="submit" class="btn btn-primary float-end">Salvar dados</button>
                </div>
            </div>
            </form>
        </div>
    </div>
@stop
