<?php

namespace Modulos\RH\Services;

use Illuminate\Support\Collection;
use Modulos\RH\Models\Colaborador;
use Modulos\RH\Models\ColaboradorFuncao;
use Modulos\RH\Models\GestorSetor;

class AprovadorPontoService
{
    public function resolverAprovadores(Colaborador $colaborador): Collection
    {
        $gestorDireto = $this->resolverGestorDireto($colaborador);

        if ($gestorDireto) {
            return collect([$gestorDireto]);
        }

        $setorIds = ColaboradorFuncao::query()
            ->where('cfn_col_id', $colaborador->col_id)
            ->whereNull('cfn_data_fim')
            ->pluck('cfn_set_id')
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (empty($setorIds)) {
            return collect();
        }

        $gestorIds = GestorSetor::query()
            ->whereIn('gst_set_id', $setorIds)
            ->where('gst_ativo', true)
            ->pluck('gst_col_id')
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (empty($gestorIds)) {
            return collect();
        }

        return Colaborador::with('pessoa')
            ->whereIn('col_id', $gestorIds)
            ->where('col_status', 'ativo')
            ->orderBy('col_id')
            ->get();
    }

    public function listarColaboradorIdsAprovaveis(Colaborador $aprovador): array
    {
        $subordinadosDiretos = Colaborador::query()
            ->where('col_status', 'ativo')
            ->where('col_gestor_id', $aprovador->col_id)
            ->pluck('col_id')
            ->all();

        $setorIds = GestorSetor::query()
            ->where('gst_col_id', $aprovador->col_id)
            ->where('gst_ativo', true)
            ->pluck('gst_set_id')
            ->filter()
            ->unique()
            ->values()
            ->all();

        $subordinadosPorSetor = [];

        if (!empty($setorIds)) {
            $subordinadosPorSetor = Colaborador::query()
                ->join('reh_colaboradores_funcoes as cfn', function ($join) {
                    $join->on('cfn.cfn_col_id', '=', 'reh_colaboradores.col_id')
                        ->whereNull('cfn.cfn_data_fim');
                })
                ->where('reh_colaboradores.col_status', 'ativo')
                ->whereNull('reh_colaboradores.col_gestor_id')
                ->whereIn('cfn.cfn_set_id', $setorIds)
                ->pluck('reh_colaboradores.col_id')
                ->all();
        }

        return array_values(array_unique(array_merge($subordinadosDiretos, $subordinadosPorSetor)));
    }

    public function podeAprovar(Colaborador $aprovador, Colaborador $colaborador): bool
    {
        return $this->resolverAprovadores($colaborador)
            ->pluck('col_id')
            ->contains($aprovador->col_id);
    }

    private function resolverGestorDireto(Colaborador $colaborador): ?Colaborador
    {
        if (!$colaborador->col_gestor_id) {
            return null;
        }

        return Colaborador::with('pessoa')
            ->where('col_id', $colaborador->col_gestor_id)
            ->where('col_status', 'ativo')
            ->first();
    }
}
