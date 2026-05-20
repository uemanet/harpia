<?php

namespace Modulos\RH\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modulos\RH\Models\AprovacaoPonto;
use Modulos\RH\Models\Colaborador;
use Modulos\RH\Models\EventoAcesso;
use Modulos\RH\Models\JornadaRemota;

class AprovacaoPontoService
{
    public function __construct(
        private AprovadorPontoService $aprovadorPontoService,
        private HoraTrabalhadaDiariaService $horaTrabalhadaDiariaService
    ) {
    }

    public function aprovar(JornadaRemota $jornada, Colaborador $aprovador): AprovacaoPonto
    {
        $this->validarJornadaPendente($jornada);
        $this->validarPermissao($jornada, $aprovador);

        $aprovacao = DB::transaction(function () use ($jornada, $aprovador) {
            $jornada->fill([
                'jor_status' => 'aprovado',
                'jor_horas_aprovadas' => $jornada->jor_horas_calculadas ?: '00:00:00',
                'jor_motivo_aprovacao' => null,
                'jor_aprovador_col_id' => $aprovador->col_id,
                'jor_aprovado_em' => Carbon::now()->toDateTimeString(),
            ])->save();

            $this->atualizarEventosDaJornada(
                $jornada,
                'aprovado',
                'Jornada remota aprovada integralmente pelo gestor.'
            );

            $aprovacao = AprovacaoPonto::create([
                'apr_eva_id' => $this->resolverEventoRepresentante($jornada),
                'apr_jor_id' => $jornada->jor_id,
                'apr_aprovador_col_id' => $aprovador->col_id,
                'apr_data_aprovacao' => Carbon::now()->toDateTimeString(),
                'apr_status' => 'aprovado',
                'apr_motivo' => null,
                'apr_hora_ajustada' => null,
                'apr_horas_aceitas' => $jornada->jor_horas_calculadas ?: '00:00:00',
            ]);

            $this->recalcularHorasDaJornada($jornada);

            return $aprovacao;
        });

        Log::info('Registro remoto aprovado.', [
            'jornada_id' => $jornada->jor_id,
            'evento_saida_id' => $jornada->jor_eva_saida_id,
            'colaborador_id' => $jornada->jor_col_id,
            'aprovador_id' => $aprovador->col_id,
        ]);

        return $aprovacao;
    }

    public function aprovarParcial(JornadaRemota $jornada, Colaborador $aprovador, string $horasAceitas, string $motivo): AprovacaoPonto
    {
        $this->validarJornadaPendente($jornada);
        $this->validarPermissao($jornada, $aprovador);

        $horasAceitas = $this->normalizarHoras($horasAceitas);
        $motivo = trim($motivo);

        if ($motivo === '') {
            throw new \InvalidArgumentException('Informe o motivo da aprovacao parcial.');
        }

        $totalAceitoSegundos = $this->converterHorasParaSegundos($horasAceitas);
        $totalCalculadoSegundos = $this->converterHorasParaSegundos((string) ($jornada->jor_horas_calculadas ?: '00:00:00'));

        if ($totalAceitoSegundos <= 0) {
            throw new \InvalidArgumentException('Informe uma quantidade de horas aceita maior que zero.');
        }

        if ($totalAceitoSegundos > $totalCalculadoSegundos) {
            throw new \InvalidArgumentException('As horas aceitas nao podem ser maiores que as horas calculadas da jornada.');
        }

        $aprovacao = DB::transaction(function () use ($jornada, $aprovador, $horasAceitas, $motivo) {
            $jornada->fill([
                'jor_status' => 'parcial',
                'jor_horas_aprovadas' => $horasAceitas,
                'jor_motivo_aprovacao' => $motivo,
                'jor_aprovador_col_id' => $aprovador->col_id,
                'jor_aprovado_em' => Carbon::now()->toDateTimeString(),
            ])->save();

            $this->atualizarEventosDaJornada(
                $jornada,
                'aprovado',
                'Jornada remota aprovada parcialmente pelo gestor.'
            );

            $aprovacao = AprovacaoPonto::create([
                'apr_eva_id' => $this->resolverEventoRepresentante($jornada),
                'apr_jor_id' => $jornada->jor_id,
                'apr_aprovador_col_id' => $aprovador->col_id,
                'apr_data_aprovacao' => Carbon::now()->toDateTimeString(),
                'apr_status' => 'parcial',
                'apr_motivo' => $motivo,
                'apr_hora_ajustada' => null,
                'apr_horas_aceitas' => $horasAceitas,
            ]);

            $this->recalcularHorasDaJornada($jornada);

            return $aprovacao;
        });

        Log::info('Jornada remota aprovada parcialmente.', [
            'jornada_id' => $jornada->jor_id,
            'colaborador_id' => $jornada->jor_col_id,
            'aprovador_id' => $aprovador->col_id,
            'horas_aceitas' => $horasAceitas,
            'motivo' => $motivo,
        ]);

        return $aprovacao;
    }

    public function reprovar(JornadaRemota $jornada, Colaborador $aprovador, string $motivo): AprovacaoPonto
    {
        $this->validarJornadaPendente($jornada);
        $this->validarPermissao($jornada, $aprovador);

        $motivo = trim($motivo);

        if ($motivo === '') {
            throw new \InvalidArgumentException('Informe o motivo da reprovacao.');
        }

        $aprovacao = DB::transaction(function () use ($jornada, $aprovador, $motivo) {
            $jornada->fill([
                'jor_status' => 'reprovado',
                'jor_horas_aprovadas' => '00:00:00',
                'jor_motivo_aprovacao' => $motivo,
                'jor_aprovador_col_id' => $aprovador->col_id,
                'jor_aprovado_em' => Carbon::now()->toDateTimeString(),
            ])->save();

            $this->atualizarEventosDaJornada(
                $jornada,
                'reprovado',
                'Jornada remota reprovada pelo gestor.'
            );

            $aprovacao = AprovacaoPonto::create([
                'apr_eva_id' => $this->resolverEventoRepresentante($jornada),
                'apr_jor_id' => $jornada->jor_id,
                'apr_aprovador_col_id' => $aprovador->col_id,
                'apr_data_aprovacao' => Carbon::now()->toDateTimeString(),
                'apr_status' => 'reprovado',
                'apr_motivo' => $motivo,
                'apr_hora_ajustada' => null,
                'apr_horas_aceitas' => '00:00:00',
            ]);

            $this->recalcularHorasDaJornada($jornada);

            return $aprovacao;
        });

        Log::info('Registro remoto reprovado.', [
            'jornada_id' => $jornada->jor_id,
            'colaborador_id' => $jornada->jor_col_id,
            'aprovador_id' => $aprovador->col_id,
            'motivo' => $motivo,
        ]);

        return $aprovacao;
    }

    private function validarJornadaPendente(JornadaRemota $jornada): void
    {
        if (!$jornada->jor_eva_entrada_id || !$jornada->jor_eva_saida_id) {
            throw new \InvalidArgumentException('A jornada remota informada nao possui entrada e saida validas para aprovacao.');
        }

        if ($jornada->jor_status !== 'pendente') {
            throw new \InvalidArgumentException('A jornada informada nao esta mais pendente de aprovacao.');
        }

        if (!$jornada->colaborador) {
            throw new \InvalidArgumentException('A jornada remota nao possui colaborador vinculado.');
        }
    }

    private function validarPermissao(JornadaRemota $jornada, Colaborador $aprovador): void
    {
        if (!$this->aprovadorPontoService->podeAprovar($aprovador, $jornada->colaborador)) {
            throw new \InvalidArgumentException('Voce nao possui permissao para aprovar este colaborador.');
        }
    }

    private function atualizarEventosDaJornada(JornadaRemota $jornada, string $status, string $mensagem): void
    {
        $eventoIds = array_filter([$jornada->jor_eva_entrada_id, $jornada->jor_eva_saida_id]);

        if (empty($eventoIds)) {
            return;
        }

        EventoAcesso::whereIn('eva_id', $eventoIds)->update([
            'eva_status' => $status,
            'eva_status_mensagem' => $mensagem,
        ]);
    }

    private function recalcularHorasDaJornada(JornadaRemota $jornada): void
    {
        if (!$jornada->jor_col_id || !$jornada->jor_data_referencia) {
            return;
        }

        $this->horaTrabalhadaDiariaService->atualizarOuCriar(
            $jornada->jor_col_id,
            $jornada->jor_data_referencia
        );
    }

    private function resolverEventoRepresentante(JornadaRemota $jornada): ?int
    {
        return $jornada->jor_eva_saida_id ?: $jornada->jor_eva_entrada_id;
    }

    private function normalizarHoras(string $horas): string
    {
        $horas = trim($horas);

        if (!preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $horas)) {
            throw new \InvalidArgumentException('Informe as horas aceitas no formato HH:MM ou HH:MM:SS.');
        }

        if (strlen($horas) === 5) {
            $horas .= ':00';
        }

        return $horas;
    }

    private function converterHorasParaSegundos(string $horas): int
    {
        $partes = array_map('intval', explode(':', $horas));
        return ($partes[0] * 3600) + ($partes[1] * 60) + $partes[2];
    }
}
