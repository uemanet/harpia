<?php

namespace Modulos\RH\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modulos\RH\Models\Colaborador;
use Modulos\RH\Models\EventoAcesso;
use Modulos\RH\Repositories\ConfiguracaoPontoRepository;
use Modulos\RH\Repositories\EventoAcessoRepository;
use Modulos\RH\Repositories\JornadaRemotaRepository;

class PontoRemotoService
{
    public function __construct(
        private AntiDuplicidadeService $antiDuplicidadeService,
        private AprovadorPontoService $aprovadorPontoService,
        private EventoAcessoRepository $eventoRepository,
        private ConfiguracaoPontoRepository $configuracaoRepository,
        private JornadaRemotaRepository $jornadaRemotaRepository
    ) {
    }

    public function registrar(Colaborador $colaborador, string $tipo, array $contexto = []): EventoAcesso
    {
        if (!in_array($tipo, ['entrada', 'saida'], true)) {
            throw new \InvalidArgumentException('Tipo de registro remoto invalido.');
        }

        $agora = Carbon::now();

        $this->validarAprovadores($colaborador);
        $this->validarJanelaHorario($agora);
        $this->validarAntiDuplicidade($colaborador, $tipo, $agora);
        $this->validarAtividadesDaSaida($tipo, $contexto);

        $evento = DB::transaction(function () use ($colaborador, $tipo, $agora, $contexto) {
            $evento = EventoAcesso::create([
                'eva_col_id' => $colaborador->col_id,
                'eva_dis_id' => null,
                'eva_tipo' => $tipo,
                'eva_data_hora' => $agora->toDateTimeString(),
                'eva_origem' => 'home_office',
                'eva_status' => 'pendente',
                'eva_status_mensagem' => 'Registro remoto aguardando aprovacao do gestor.',
                'eva_hash' => $this->antiDuplicidadeService->gerarHash(
                    (string) $colaborador->col_id,
                    'home_office:' . $tipo,
                    $agora->toDateTimeString()
                ),
                'eva_ip_origem' => $contexto['ip'] ?? null,
                'eva_user_agent' => $contexto['user_agent'] ?? null,
                'eva_observacao' => $this->montarObservacao($colaborador, $tipo, $contexto),
            ]);

            $this->registrarNaJornadaRemota($colaborador, $evento, $tipo, $contexto);

            return $evento;
        });

        Log::info('Registro remoto criado e enviado para aprovacao.', [
            'evento_id' => $evento->eva_id,
            'colaborador_id' => $colaborador->col_id,
            'tipo' => $tipo,
        ]);

        return $evento;
    }

    public function obterEstadoAtualDoDia(Colaborador $colaborador, ?Carbon $data = null): array
    {
        $data = $data ?: Carbon::now();
        $eventos = EventoAcesso::where('eva_col_id', $colaborador->col_id)
            ->where('eva_origem', 'home_office')
            ->whereDate('eva_data_hora', $data->format('Y-m-d'))
            ->orderBy('eva_data_hora')
            ->get();
        $jornadaAberta = $this->jornadaRemotaRepository->buscarAbertaDoColaborador($colaborador->col_id);

        return [
            'tem_entrada_aberta' => $jornadaAberta !== null,
            'ultimo_evento' => $eventos->last(),
            'jornada_aberta' => $jornadaAberta,
            'pode_entrada' => true,
            'pode_saida' => true,
            'eventos' => $eventos,
        ];
    }

    public function listarMeusRegistros(int $colaboradorId, int $limite = 30): Collection
    {
        return \Modulos\RH\Models\JornadaRemota::with(['eventoEntrada', 'eventoSaida', 'aprovador.pessoa'])
            ->where('jor_col_id', $colaboradorId)
            ->orderByRaw('COALESCE(jor_saida_em, jor_entrada_em) desc')
            ->limit($limite)
            ->get();
    }

    private function validarAprovadores(Colaborador $colaborador): void
    {
        if ($this->aprovadorPontoService->resolverAprovadores($colaborador)->isEmpty()) {
            throw new \InvalidArgumentException('Nao foi encontrado gestor responsavel para aprovar este registro remoto.');
        }
    }

    private function validarJanelaHorario(Carbon $dataHora): void
    {
        $janelaInicio = (string) $this->configuracaoRepository->getValor('ponto.janela_inicio', '05:00');
        $janelaFim = (string) $this->configuracaoRepository->getValor('ponto.janela_fim', '23:00');
        $horaAtual = $dataHora->format('H:i');

        if ($horaAtual < $janelaInicio || $horaAtual > $janelaFim) {
            throw new \InvalidArgumentException(sprintf(
                'O registro remoto esta fora da janela permitida. Horario valido: %s as %s.',
                $janelaInicio,
                $janelaFim
            ));
        }
    }

    private function validarSequencia(Colaborador $colaborador, string $tipo, Carbon $dataHora): void
    {
        $estado = $this->obterEstadoAtualDoDia($colaborador, $dataHora);

        if ($tipo === 'entrada' && $estado['tem_entrada_aberta']) {
            throw new \InvalidArgumentException('Ja existe uma entrada em aberto para o dia atual.');
        }

        if ($tipo === 'saida' && !$estado['tem_entrada_aberta']) {
            throw new \InvalidArgumentException('Nao existe entrada em aberto para registrar a saida.');
        }
    }

    private function validarAntiDuplicidade(Colaborador $colaborador, string $tipo, Carbon $dataHora): void
    {
        $janelaSegundos = (int) $this->configuracaoRepository->getValor('ponto.anti_duplicidade_seg', 60);

        $duplicadoRecente = EventoAcesso::query()
            ->where('eva_col_id', $colaborador->col_id)
            ->where('eva_origem', 'home_office')
            ->where('eva_tipo', $tipo)
            ->whereIn('eva_status', ['pendente', 'aprovado', 'processado'])
            ->where('eva_data_hora', '>=', $dataHora->copy()->subSeconds($janelaSegundos)->toDateTimeString())
            ->exists();

        if ($duplicadoRecente) {
            throw new \InvalidArgumentException('Ja existe um registro remoto recente deste tipo.');
        }

        $hash = $this->antiDuplicidadeService->gerarHash(
            (string) $colaborador->col_id,
            'home_office:' . $tipo,
            $dataHora->toDateTimeString()
        );

        if ($this->antiDuplicidadeService->existeHash($hash)) {
            throw new \InvalidArgumentException('Ja existe um registro remoto equivalente para este minuto.');
        }
    }

    private function validarAtividadesDaSaida(string $tipo, array $contexto): void
    {
        if ($tipo !== 'saida') {
            return;
        }

        $atividades = trim((string) ($contexto['atividades'] ?? ''));

        if ($atividades === '') {
            throw new \InvalidArgumentException('Informe as atividades executadas para registrar a saida remota.');
        }
    }

    private function registrarNaJornadaRemota(Colaborador $colaborador, EventoAcesso $evento, string $tipo, array $contexto): void
    {
        if ($tipo === 'entrada') {
            $this->jornadaRemotaRepository->marcarAbertasComoInconsistentes(
                $colaborador->col_id,
                'Nova entrada remota registrada antes do fechamento da jornada anterior.'
            );

            $this->jornadaRemotaRepository->abrirJornada($colaborador->col_id, $evento);

            return;
        }

        $atividades = trim((string) ($contexto['atividades'] ?? ''));
        $jornadaAberta = $this->jornadaRemotaRepository->buscarAbertaDoColaborador($colaborador->col_id);

        if (!$jornadaAberta) {
            $this->jornadaRemotaRepository->criarJornadaInconsistenteDeSaida(
                $colaborador->col_id,
                $evento,
                $atividades
            );

            return;
        }

        $this->jornadaRemotaRepository->fecharJornadaComSaida($jornadaAberta, $evento, $atividades);
    }

    private function montarObservacao(Colaborador $colaborador, string $tipo, array $contexto): string
    {
        return json_encode([
            'tipo_registro' => $tipo,
            'origem_fluxo' => $contexto['canal'] ?? 'web',
            'atividades' => trim((string) ($contexto['atividades'] ?? '')) ?: null,
            'observacao_usuario' => trim((string) ($contexto['observacao'] ?? '')) ?: null,
            'aprovadores_sugeridos' => $this->aprovadorPontoService->resolverAprovadores($colaborador)
                ->pluck('col_id')
                ->values()
                ->all(),
            'registrado_em' => Carbon::now()->toIso8601String(),
        ], JSON_UNESCAPED_UNICODE);
    }
}
