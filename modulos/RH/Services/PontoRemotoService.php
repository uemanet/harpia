<?php

namespace Modulos\RH\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Modulos\RH\Models\Colaborador;
use Modulos\RH\Models\EventoAcesso;
use Modulos\RH\Repositories\ConfiguracaoPontoRepository;
use Modulos\RH\Repositories\EventoAcessoRepository;

class PontoRemotoService
{
    public function __construct(
        private AntiDuplicidadeService $antiDuplicidadeService,
        private AprovadorPontoService $aprovadorPontoService,
        private EventoAcessoRepository $eventoRepository,
        private ConfiguracaoPontoRepository $configuracaoRepository
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
        $this->validarSequencia($colaborador, $tipo, $agora);
        $this->validarAntiDuplicidade($colaborador, $tipo, $agora);

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
        $eventos = $this->eventoRepository->buscarEventosDoDia($colaborador->col_id, $data->format('Y-m-d'));

        $entradaAberta = false;
        $ultimoEvento = null;

        foreach ($eventos as $evento) {
            $ultimoEvento = $evento;

            if ($evento['eva_tipo'] === 'entrada') {
                $entradaAberta = true;
            } elseif ($evento['eva_tipo'] === 'saida') {
                $entradaAberta = false;
            }
        }

        return [
            'tem_entrada_aberta' => $entradaAberta,
            'ultimo_evento' => $ultimoEvento,
            'pode_entrada' => !$entradaAberta,
            'pode_saida' => $entradaAberta,
            'eventos' => $eventos,
        ];
    }

    public function listarMeusRegistros(int $colaboradorId, int $limite = 30): Collection
    {
        return EventoAcesso::where('eva_col_id', $colaboradorId)
            ->orderByDesc('eva_data_hora')
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

    private function montarObservacao(Colaborador $colaborador, string $tipo, array $contexto): string
    {
        return json_encode([
            'tipo_registro' => $tipo,
            'origem_fluxo' => $contexto['canal'] ?? 'web',
            'observacao_usuario' => trim((string) ($contexto['observacao'] ?? '')) ?: null,
            'aprovadores_sugeridos' => $this->aprovadorPontoService->resolverAprovadores($colaborador)
                ->pluck('col_id')
                ->values()
                ->all(),
            'registrado_em' => Carbon::now()->toIso8601String(),
        ], JSON_UNESCAPED_UNICODE);
    }
}
