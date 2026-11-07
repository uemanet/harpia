@extends('layouts.site')

@section('content')
    <div class="card shadow-sm border-success">
        <div class="card-body py-5">
            <h3 class="text-success mb-3">✔ Foto Enviada!</h3>
            <p>Sua foto facial foi registrada com sucesso e já está disponível para o controle de acesso.</p>
            <a href="{{ route('portal.foto.create') }}" class="btn btn-outline-secondary mt-3">Voltar ao início</a>
        </div>
    </div>
@stop
