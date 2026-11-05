@extends('layouts.site')



@section('scripts')
    @vite('modulos/RH/Resources/js/pages/portal-foto/upload.js')
@endsection

@section('content')
    <div class="card shadow-sm border-0">
        <div class="card-body p-4 p-md-5">

            <div class="text-center mb-4">
                <h5 class="fw-bold text-dark mb-1">Olá, {{ $colaborador->pessoa->pes_nome }}</h5>
                <p class="text-muted small">Envie ou atualize sua foto frontal nítida para o reconhecimento facial da catraca.</p>
            </div>

            <div class="text-center mb-4">
                <!-- Verifica se já tem foto e carrega a rota segura, caso contrário oculta a tag -->
                @if($colaborador->col_foto_anx_id)
                    <span class="badge bg-secondary mb-2">Foto atual</span><br>
                    <img id="preview" src="{{ route('portal.foto.atual') }}" class="foto-preview" style="height: 150px;">
                @else
                    <img id="preview" src="" class="foto-preview" style="display: none;">
                @endif
            </div>

            <form id="fotoForm" action="{{ route('portal.foto.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Selecione uma nova foto:</label>
                    <input type="file" id="fotoInput" name="col_foto_facial" class="form-control" accept="image/jpeg, image/png" required>
{{--                    <input type="file" id="fotoInput" name="col_foto_facial" class="form-control" accept="image/jpeg, image/png" required onchange="carregarPrevia()">--}}
                </div>

                <div id="statusValidador" class="alert alert-info py-2" style="display: none; font-size: 0.9rem;">
                    Analisando foto...
                </div>

                <button type="submit" id="btnSubmit" class="btn btn-success w-100 py-2 fw-bold" disabled>
                    Salvar Nova Foto
                </button>
            </form>

            <div class="text-center mt-4">
                <small class="text-muted">Copyright &copy; 2016-{{ date('Y') }} UemaNet. All rights reserved.</small>
            </div>
        </div>
    </div>
@stop
