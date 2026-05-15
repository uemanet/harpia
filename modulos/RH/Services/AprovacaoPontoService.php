<?php

namespace Modulos\RH\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Modulos\RH\Models\AprovacaoPonto;
use Modulos\RH\Models\Colaborador;
use Modulos\RH\Models\EventoAcesso;

class AprovacaoPontoService
{
    public function __construct(
        private AprovadorPontoService $aprovadorPontoService,
        private HoraTrabalhadaDiariaService $horaTrabalhadaDiariaService
    ) {
    }

    public function aprovar(EventoAcesso $evento, Colaborador $aprovador): AprovacaoPonto
    {
        $this->validarEventoPendente($evento);
        $this->validarPermissao($evento, $aprovador);

        $evento->fill([
            'eva_status' => 'aprovado',
            'eva_status_mensagem' => 'Registro remoto aprovado pelo gestor.',
        ])->save();

        $aprovacao = AprovacaoPonto::create([
            'apr_eva_id' => $evento->eva_id,
            'apr_aprovador_col_id' => $aprovador->col_id,
            'apr_data_aprovacao' => Carbon::now()->toDateTimeString(),
            'apr_status' => 'aprovado',
            'apr_motivo' => null,
            'apr_hora_ajustada' => null,
        ]);

        if ($evento->eva_col_id && $evento->eva_data_hora) {
            $this->horaTrabalhadaDiariaService->atualizarOuCriar(
                $evento->eva_col_id,
                date('Y-m-d', strtotime($evento->eva_data_hora))
            );
        }

        Log::info('Registro remoto aprovado.', [
            'evento_id' => $evento->eva_id,
            'colaborador_id' => $evento->eva_col_id,
            'aprovador_id' => $aprovador->col_id,
        ]);

        return $aprovacao;
    }

    public function reprovar(EventoAcesso $evento, Colaborador $aprovador, string $motivo): AprovacaoPonto
    {
        $this->validarEventoPendente($evento);
        $this->validarPermissao($evento, $aprovador);

        $motivo = trim($motivo);

        if ($motivo === '') {
            throw new \InvalidArgumentException('Informe o motivo da reprovacao.');
        }

        $evento->fill([
            'eva_status' => 'reprovado',
            'eva_status_mensagem' => 'Registro remoto reprovado pelo gestor.',
        ])->save();

        $aprovacao = AprovacaoPonto::create([
            'apr_eva_id' => $evento->eva_id,
            'apr_aprovador_col_id' => $aprovador->col_id,
            'apr_data_aprovacao' => Carbon::now()->toDateTimeString(),
            'apr_status' => 'reprovado',
            'apr_motivo' => $motivo,
            'apr_hora_ajustada' => null,
        ]);

        Log::info('Registro remoto reprovado.', [
            'evento_id' => $evento->eva_id,
            'colaborador_id' => $evento->eva_col_id,
            'aprovador_id' => $aprovador->col_id,
            'motivo' => $motivo,
        ]);

        return $aprovacao;
    }

    private function validarEventoPendente(EventoAcesso $evento): void
    {
        if ($evento->eva_origem !== 'home_office') {
            throw new \InvalidArgumentException('A aprovacao e permitida apenas para registros remotos.');
        }

        if ($evento->eva_status !== 'pendente') {
            throw new \InvalidArgumentException('O registro informado nao esta mais pendente de aprovacao.');
        }

        if (!$evento->colaborador) {
            throw new \InvalidArgumentException('O registro remoto nao possui colaborador vinculado.');
        }
    }

    private function validarPermissao(EventoAcesso $evento, Colaborador $aprovador): void
    {
        if (!$this->aprovadorPontoService->podeAprovar($aprovador, $evento->colaborador)) {
            throw new \InvalidArgumentException('Voce nao possui permissao para aprovar este colaborador.');
        }
    }
}
