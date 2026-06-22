@extends('layouts.modulos.default')

@section('title')
    Atribuir permissões
@stop

@section('subtitle')
    <b>Módulo:</b> {{ $perfil->modulo->mod_nome }} | <b>Perfil:</b> {{ $perfil->prf_nome }}
@stop

@section('content')
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title mb-3">Selecione as permissões</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('seguranca.perfis.atribuirpermissoes', [$perfil->prf_id]) }}" method="POST" id="formPermissoes">
                @csrf
                <!-- Input hidden que o Controller espera receber com os IDs separados por vírgula -->
                <input type="hidden" name="permissao" id="permissao">
                <input type="hidden" name="prf_id" value="{{ $perfil->prf_id }}">

                <div class="row">
                    @if(count($permissoes))
                        @foreach($permissoes as $permissao)
                            <div class="col-md-12 mb-3">
                                <div class="card bg-light shadow-none border">
                                    <div class="card-header py-2">
                                        <div class="form-check">
                                            <!-- Checkbox pai para marcar/desmarcar todo o grupo -->
                                            <input class="form-check-input check-all-grupo" type="checkbox" id="grupo_{{ $loop->index }}">
                                            <label class="form-check-label fw-bold" for="grupo_{{ $loop->index }}" style="cursor: pointer;">
                                                <i class="fa fa-folder text-warning me-2"></i> {{ ucfirst($permissao['rcs_nome']) }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="card-body py-2">
                                        <div class="row">
                                            @if(count($permissao['permissoes']))
                                                @foreach($permissao['permissoes'] as $perm)
                                                    <div class="col-md-3 col-sm-4 col-6 mb-2">
                                                        <div class="form-check">
                                                            <!-- Checkbox filho (a permissão em si) -->
                                                            <input class="form-check-input perm-checkbox" type="checkbox"
                                                                   value="{{ $perm['prm_id'] }}"
                                                                   id="prm_{{ $perm['prm_id'] }}"
                                                                   @if($perm['habilitado']) checked @endif>
                                                            <label class="form-check-label" for="prm_{{ $perm['prm_id'] }}" style="cursor: pointer;">
                                                                <i class="fa fa-cog text-success me-1"></i> {{ $perm['prm_nome'] }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12">
                            <div class="alert alert-info">Nenhuma permissão encontrada para este módulo.</div>
                        </div>
                    @endif
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary float-end" id="btn-enviar">
                            <i class="fa fa-save me-1"></i> Atribuir permissões ao perfil
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

@section('scripts')
    @vite('modulos/Seguranca/Resources/js/pages/perfis/atribuirpermissoes.js')
@stop