@extends('layouts.modulos.default')

@section('title')
    Usuários do Dispositivo
@stop

@section('subtitle')
    Gestão Control iD
@stop

@section('content')
    <div id="card-progresso-sincronizacao" class="row mb-3 d-none">
        <div class="card card-info card-outline">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title m-0">
                    <i id="sync-icone-status" class="fa fa-refresh fa-spin me-1"></i>
                    <span id="sync-titulo-status">Sincronizando colaboradores ativos...</span>
                </h3>
                <span id="sync-badge-percentual" class="badge bg-info">0%</span>
            </div>
            <div class="card-body">
                <p id="sync-detalhe-dispositivo" class="mb-2 text-muted">
                    Preparando lotes de sincronização...
                </p>
                <div class="progress mb-3" style="height: 20px;">
                    <div id="sync-barra-progresso"
                         class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                         role="progressbar"
                         style="width: 0%;"
                         aria-valuenow="0"
                         aria-valuemin="0"
                         aria-valuemax="100">0%</div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-success">Criados: <strong id="sync-cnt-criados">0</strong></span>
                    <span class="badge bg-primary">Atualizados/Vinculados: <strong id="sync-cnt-atualizados">0</strong></span>
                    <span class="badge bg-secondary">Já compatíveis: <strong id="sync-cnt-inalterados">0</strong></span>
                    <span class="badge bg-warning text-dark">Sem foto: <strong id="sync-cnt-pendentes-foto">0</strong></span>
                    <span id="sync-badge-duplicados" class="badge bg-info text-dark d-none">Duplicados removidos: <strong id="sync-cnt-duplicados">0</strong></span>
                    <span class="badge bg-danger">Falhas: <strong id="sync-cnt-falhas">0</strong></span>
                </div>
                <div id="sync-alerta-erro" class="alert alert-danger mt-3 mb-0 d-none"></div>
            </div>
        </div>
    </div>

    @if(session('falhas_sincronizacao'))
        @php($falhasSincronizacao = session('falhas_sincronizacao'))
        <div class="row mb-3">
            <div class="card card-danger card-outline">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title m-0 text-danger">
                        <i class="fa fa-exclamation-triangle"></i>
                        Colaboradores com falha na sincronização ({{ count($falhasSincronizacao) }})
                    </h3>
                    <button type="button" class="btn-close" onclick="this.closest('.row').remove()" aria-label="Fechar"></button>
                </div>
                <div class="card-body p-0">
                    <div class="px-3 py-2 text-muted border-bottom">
                        Os demais colaboradores ativos foram processados normalmente. Corrija os itens abaixo e execute a sincronização novamente:
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mb-0">
                            <thead>
                            <tr>
                                <th>Colaborador</th>
                                <th style="width: 140px;">Matrícula</th>
                                <th>Dispositivo(s)</th>
                                <th>Motivo</th>
                                <th style="width: 140px;">Ação</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($falhasSincronizacao as $falha)
                                <tr>
                                    <td>{{ $falha['nome'] }}</td>
                                    <td>{{ $falha['registration'] }}</td>
                                    <td>{{ implode(', ', $falha['dispositivos'] ?? []) }}</td>
                                    <td class="text-danger">{{ $falha['mensagem'] }}</td>
                                    <td>
                                        <a href="{{ route('rh.colaboradores.edit', ['id' => $falha['col_id']]) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-danger">
                                            <i class="fa fa-pencil"></i> Editar foto
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title m-0"><i class="fa fa-filter"></i> Selecionar dispositivo</h3>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('rh.dispositivousuarios.index') }}">
                    <div class="row">
                        <div class="col-md-10 px-1">
                            <select name="dis_id" class="form-control">
                                <option value="">Selecione um dispositivo</option>
                                @foreach($dispositivos as $dispositivoId => $dispositivoNome)
                                    <option value="{{ $dispositivoId }}" {{ (string) request('dis_id') === (string) $dispositivoId ? 'selected' : '' }}>
                                        {{ $dispositivoNome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 px-1">
                            <button type="submit" class="btn btn-primary w-100">Carregar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($dispositivo)
        <div class="row mt-2">
            <div class="col-sm-4">
                <div class="small-box text-bg-info">
                    <div class="inner">
                        <h3>{{ $resumo['total'] ?? 0 }}</h3>
                        <p>Usuários encontrados</p>
                    </div>
                    <i class="small-box-icon fa fa-users"></i>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="small-box text-bg-success">
                    <div class="inner">
                        <h3>{{ $resumo['vinculados'] ?? 0 }}</h3>
                        <p>Vinculados ao Harpia</p>
                    </div>
                    <i class="small-box-icon fa fa-link"></i>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="small-box text-bg-warning">
                    <div class="inner">
                        <h3>{{ $resumo['sem_foto'] ?? 0 }}</h3>
                        <p>Sem foto facial</p>
                    </div>
                    <i class="small-box-icon fa fa-camera"></i>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <div class="row">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title m-0">Cadastrar no dispositivo</h3>
                        </div>
                        <form method="POST" action="{{ route('rh.dispositivousuarios.create') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="dis_id" value="{{ $dispositivo->dis_id }}">

                            <div class="card-body">
                                <div class="form-group @if($errors->has('col_id')) has-error @endif">
                                    <label>Colaborador</label>
                                    <select name="col_id" class="form-control">
                                        <option value="">Selecione</option>
                                        @foreach($colaboradores as $colaboradorId => $colaboradorNome)
                                            <option value="{{ $colaboradorId }}" {{ old('col_id') == $colaboradorId ? 'selected' : '' }}>
                                                {{ $colaboradorNome }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('col_id')) <p class="help-block">{{ $errors->first('col_id') }}</p> @endif
                                </div>

                                <p class="text-muted">
                                    Nome, registration e foto facial serão obtidos automaticamente do cadastro do colaborador.
                                </p>

                                <div class="form-check">
                                    <input type="hidden" name="cadastrar_em_todos_dispositivos" value="0">
                                    <input type="checkbox" class="form-check-input" name="cadastrar_em_todos_dispositivos" value="1" {{ old('cadastrar_em_todos_dispositivos', '1') === '1' ? 'checked' : '' }}>
                                    <label class="form-check-label">Cadastrar em todos os aparelhos ativos</label>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fa fa-plus"></i> Cadastrar no dispositivo
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="card card-secondary card-outline">
                        <div class="card-header">
                            <h3 class="card-title m-0">Ações</h3>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('rh.dispositivousuarios.sincronizar') }}" class="mb-2">
                                {{ csrf_field() }}
                                <input type="hidden" name="dis_id" value="{{ $dispositivo->dis_id }}">
                                <button type="submit" id="btn-sincronizar-dispositivo" class="btn btn-primary w-100">
                                    <i class="fa fa-refresh"></i> Sincronizar do dispositivo
                                </button>
                            </form>

                            <form id="form-sincronizar-todos" method="POST" action="{{ route('rh.dispositivousuarios.sincronizartodos') }}" class="mb-2">
                                {{ csrf_field() }}
                                <input type="hidden" name="dis_id" value="{{ $dispositivo->dis_id }}">

                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="sincronizar_todos_dispositivos" value="1" {{ old('sincronizar_todos_dispositivos', session()->hasOldInput() ? 0 : 1) ? 'checked' : '' }}>
                                    <label class="form-check-label">Sincronizar em todos os aparelhos ativos</label>
                                </div>

                                <button type="submit" id="btn-sincronizar-todos" class="btn btn-success w-100" onclick="return confirm('Tem certeza que deseja sincronizar os colaboradores ativos do Harpia para o dispositivo atual ou para todos os aparelhos ativos?')">
                                    <i class="fa fa-exchange"></i> Sincronizar colaboradores ativos
                                </button>
                            </form>

                            <a href="{{ route('rh.vincularcolaboradores.index', ['dis_id' => $dispositivo->dis_id]) }}" class="btn btn-warning w-100">
                                <i class="fa fa-link"></i> Abrir tela de vinculação
                            </a>

                            <a href="{{ route('rh.dispositivousuarios.exportarcsv', ['id' => $dispositivo->dis_id]) }}" class="btn btn-secondary w-100 mt-2">
                                <i class="fa fa-download"></i> Exportar CSV Control iD
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-8">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title m-0">Usuários lidos do dispositivo</h3>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        @if(count($usuarios))
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>ID iDFace</th>
                                    <th>Registration</th>
                                    <th>Nome</th>
                                    <th>Foto</th>
                                    <th>Vinculação</th>
                                    <th style="width: 210px;">Ações</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($usuarios as $usuario)
                                    <tr>
                                        <td>{{ $usuario['user_id'] }}</td>
                                        <td>{{ $usuario['registration'] ?: '-' }}</td>
                                        <td>{{ $usuario['nome'] ?: '-' }}</td>
                                        <td>
                                            @if($usuario['foto_ok'])
                                                <span class="badge bg-success">Foto OK</span>
                                            @else
                                                <span class="badge bg-warning">Sem foto</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($usuario['col_id'])
                                                <span class="badge bg-success">Vinculado</span>
                                                <div>{{ $usuario['colaborador_nome'] }}</div>
                                            @else
                                                <span class="badge bg-warning">Pendente</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('rh.dispositivousuarios.edit', ['id' => $usuario['user_id'], 'dis_id' => $dispositivo->dis_id]) }}" class="btn btn-sm btn-primary">
                                                <i class="fa fa-pencil"></i> Editar
                                            </a>

                                            <form method="POST" action="{{ route('rh.dispositivousuarios.delete') }}" style="display: inline;">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="dis_id" value="{{ $dispositivo->dis_id }}">
                                                <input type="hidden" name="user_id" value="{{ $usuario['user_id'] }}">
                                                <input type="hidden" name="todos_dispositivos" value="0">
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        data-registration="{{ $usuario['registration'] }}"
                                                        onclick="return confirmarExclusao(this)">
                                                    <i class="fa fa-trash"></i> Excluir
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-info m-3">
                                Nenhum usuário retornado pelo dispositivo selecionado.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
@stop

@section('scripts')
    <script type="text/javascript">
        function confirmarExclusao(botao) {
            var form = botao.closest('form');
            var registration = botao.getAttribute('data-registration');

            if (!confirm('Tem certeza que deseja remover este usuário do dispositivo?')) {
                return false;
            }

            if (registration && registration !== '') {
                if (confirm('Este usuário possui registration "' + registration + '".\n\nDeseja removê-lo de TODOS os dispositivos?\n\nOK = Excluir de todos\nCancelar = Excluir apenas deste')) {
                    form.querySelector('input[name="todos_dispositivos"]').value = '1';
                }
            }

            return true;
        }

        (function () {
            var form = document.getElementById('form-sincronizar-todos');
            if (!form || typeof window.fetch !== 'function' || typeof window.URLSearchParams !== 'function') {
                return;
            }

            var cardProgresso = document.getElementById('card-progresso-sincronizacao');
            var iconeStatus = document.getElementById('sync-icone-status');
            var tituloStatus = document.getElementById('sync-titulo-status');
            var badgePercentual = document.getElementById('sync-badge-percentual');
            var detalheDispositivo = document.getElementById('sync-detalhe-dispositivo');
            var barraProgresso = document.getElementById('sync-barra-progresso');
            var cntCriados = document.getElementById('sync-cnt-criados');
            var cntAtualizados = document.getElementById('sync-cnt-atualizados');
            var cntInalterados = document.getElementById('sync-cnt-inalterados');
            var cntPendentesFoto = document.getElementById('sync-cnt-pendentes-foto');
            var badgeDuplicados = document.getElementById('sync-badge-duplicados');
            var cntDuplicados = document.getElementById('sync-cnt-duplicados');
            var cntFalhas = document.getElementById('sync-cnt-falhas');
            var alertaErro = document.getElementById('sync-alerta-erro');
            var btnSincronizarTodos = document.getElementById('btn-sincronizar-todos');
            var btnSincronizarDispositivo = document.getElementById('btn-sincronizar-dispositivo');

            function atualizarInterface(dados) {
                var percentual = Math.max(0, Math.min(100, parseInt(dados.progresso_percentual || 0, 10)));
                barraProgresso.style.width = percentual + '%';
                barraProgresso.setAttribute('aria-valuenow', String(percentual));
                barraProgresso.textContent = percentual + '%';
                badgePercentual.textContent = percentual + '%';

                if (dados.resumo) {
                    cntCriados.textContent = String(dados.resumo.criados || 0);
                    cntAtualizados.textContent = String(dados.resumo.atualizados || 0);
                    cntInalterados.textContent = String(dados.resumo.inalterados || 0);
                    cntPendentesFoto.textContent = String(dados.resumo.pendentes_foto || 0);
                    cntFalhas.textContent = String(dados.resumo.falhas || 0);
                }

                var dupRemovidos = parseInt(dados.duplicados_removidos || 0, 10);
                if (badgeDuplicados && cntDuplicados) {
                    cntDuplicados.textContent = String(dupRemovidos);
                    if (dupRemovidos > 0) {
                        badgeDuplicados.classList.remove('d-none');
                    }
                }

                if (dados.concluido) {
                    tituloStatus.textContent = 'Sincronização concluída! Atualizando listagem...';
                    detalheDispositivo.textContent = 'Redirecionando para exibir o resultado consolidado...';
                    return;
                }

                var totalDispositivos = parseInt(dados.total_dispositivos || 1, 10);
                var indiceAtual = Math.min(totalDispositivos, parseInt(dados.indice_dispositivo_atual || 0, 10) + 1);
                var nomeDispositivo = dados.dispositivo_atual || 'Dispositivo';
                var offsetAtual = parseInt(dados.offset_atual || 0, 10);
                var totalCol = parseInt(dados.total_colaboradores || 0, 10);

                if (dados.aguardando_lock) {
                    detalheDispositivo.textContent = 'Aguardando conclusão de operação concorrente em ' + nomeDispositivo + '...';
                    return;
                }

                detalheDispositivo.textContent = 'Dispositivo ' + indiceAtual + ' de ' + totalDispositivos + ': '
                    + nomeDispositivo + ' (' + offsetAtual + ' de ' + totalCol + ' colaboradores verificados)';
            }

            function aguardar(ms) {
                return new Promise(function (resolve) {
                    window.setTimeout(resolve, ms);
                });
            }

            async function enviarEtapa(camposBase, camposExtras, tentativa) {
                var params = new URLSearchParams();
                Object.keys(camposBase).forEach(function (chave) {
                    if (camposBase[chave] !== null && camposBase[chave] !== undefined) {
                        params.append(chave, camposBase[chave]);
                    }
                });
                Object.keys(camposExtras).forEach(function (chave) {
                    params.append(chave, camposExtras[chave]);
                });

                var resposta;
                try {
                    resposta = await window.fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': camposBase._token || ''
                        },
                        credentials: 'same-origin',
                        body: params.toString()
                    });
                } catch (erroRede) {
                    if (tentativa < 1) {
                        await aguardar(1000);
                        return enviarEtapa(camposBase, camposExtras, tentativa + 1);
                    }
                    throw new Error('Falha de comunicação com o servidor durante a sincronização.');
                }

                if ((resposta.status === 502 || resposta.status === 503 || resposta.status === 504) && tentativa < 1) {
                    await aguardar(1000);
                    return enviarEtapa(camposBase, camposExtras, tentativa + 1);
                }

                var texto = await resposta.text();
                var json = null;

                try {
                    json = texto ? JSON.parse(texto) : null;
                } catch (e) {
                    json = null;
                }

                if (!resposta.ok || !json) {
                    var msgErro = (json && (json.erro || json.message))
                        ? (json.erro || json.message)
                        : ('O servidor retornou um erro inesperado (HTTP ' + resposta.status + ').');
                    throw new Error(msgErro);
                }

                return json;
            }

            form.addEventListener('submit', async function (event) {
                event.preventDefault();

                if (form.dataset.sincronizando === '1') {
                    return;
                }

                form.dataset.sincronizando = '1';

                if (btnSincronizarTodos) {
                    btnSincronizarTodos.disabled = true;
                }
                if (btnSincronizarDispositivo) {
                    btnSincronizarDispositivo.disabled = true;
                }

                alertaErro.classList.add('d-none');
                alertaErro.textContent = '';
                iconeStatus.className = 'fa fa-refresh fa-spin me-1';
                barraProgresso.classList.add('progress-bar-animated');
                tituloStatus.textContent = 'Sincronizando colaboradores ativos...';
                cardProgresso.classList.remove('d-none');
                cardProgresso.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

                var formData = new FormData(form);
                var camposBase = {
                    _token: formData.get('_token') || '',
                    dis_id: formData.get('dis_id') || ''
                };

                if (formData.get('sincronizar_todos_dispositivos')) {
                    camposBase.sincronizar_todos_dispositivos = formData.get('sincronizar_todos_dispositivos');
                }

                try {
                    var dados = await enviarEtapa(camposBase, { acao_lote: 'iniciar' }, 0);
                    atualizarInterface(dados);

                    while (!dados.concluido) {
                        if (dados.aguardando_lock) {
                            await aguardar(1500);
                        }
                        dados = await enviarEtapa(camposBase, {
                            acao_lote: 'processar',
                            token: dados.token
                        }, 0);
                        atualizarInterface(dados);
                    }

                    if (dados.redirect_url) {
                        window.location.href = dados.redirect_url;
                        return;
                    }

                    window.location.reload();
                } catch (erro) {
                    form.dataset.sincronizando = '0';
                    iconeStatus.className = 'fa fa-exclamation-triangle text-danger me-1';
                    barraProgresso.classList.remove('progress-bar-animated');
                    tituloStatus.textContent = 'A sincronização foi interrompida.';
                    alertaErro.textContent = erro && erro.message ? erro.message : 'Não foi possível concluir a sincronização.';
                    alertaErro.classList.remove('d-none');

                    if (btnSincronizarTodos) {
                        btnSincronizarTodos.disabled = false;
                    }
                    if (btnSincronizarDispositivo) {
                        btnSincronizarDispositivo.disabled = false;
                    }
                }
            });
        })();
    </script>
@endsection
