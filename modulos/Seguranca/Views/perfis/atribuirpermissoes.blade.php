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
    <script type="text/javascript">
        $(document).ready(function() {
            // Lógica para marcar/desmarcar todos os itens de uma pasta
            $('.check-all-grupo').on('change', function() {
                var isChecked = $(this).is(':checked');
                // Encontra todos os checkboxes de permissão dentro deste card e aplica o mesmo estado
                $(this).closest('.card').find('.perm-checkbox').prop('checked', isChecked);
            });

            // Lógica para atualizar a caixa "pai" se o usuário clicar individualmente nos "filhos"
            $('.perm-checkbox').on('change', function() {
                var $card = $(this).closest('.card');
                var total = $card.find('.perm-checkbox').length;
                var checked = $card.find('.perm-checkbox:checked').length;

                // Se todos os filhos estiverem marcados, marca o pai. Se não, desmarca o pai.
                $card.find('.check-all-grupo').prop('checked', total === checked && total > 0);

                // (Opcional) Adiciona o estado indeterminado se tiver apenas alguns marcados
                $card.find('.check-all-grupo').prop('indeterminate', checked > 0 && checked < total);
            });

            // Inicializa o estado visual das pastas ao carregar a página
            $('.card').each(function() {
                var total = $(this).find('.perm-checkbox').length;
                var checked = $(this).find('.perm-checkbox:checked').length;

                if (total > 0) {
                    if (checked === total) {
                        $(this).find('.check-all-grupo').prop('checked', true);
                    } else if (checked > 0) {
                        $(this).find('.check-all-grupo').prop('indeterminate', true);
                    }
                }
            });

            // Intercepta o envio do formulário para agrupar os IDs na vírgula (igual ao código legado)
            $('#formPermissoes').on('submit', function(e) {
                e.preventDefault();

                var checked_ids = [];

                // Varre todos os checkboxes marcados e pega o "value" (que é o ID)
                $('.perm-checkbox:checked').each(function() {
                    checked_ids.push($(this).val());
                });

                // Transforma a array em uma string com vírgulas ex: "1,2,5,8"
                $('#permissao').val(checked_ids.join(','));

                // Submete o formulário nativamente
                this.submit();
            });
        });
    </script>
@stop