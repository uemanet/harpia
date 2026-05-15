<?php

namespace Modulos\RH\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\RH\Models\GestorSetor;

class GestorSetorRepository extends BaseRepository
{
    public function __construct(GestorSetor $gestorSetor)
    {
        $this->model = $gestorSetor;
    }

    public function syncGestores(int $setId, array $colIds): void
    {
        $atuais = $this->model->where('gst_set_id', $setId)->pluck('gst_col_id')->toArray();

        $paraRemover = array_diff($atuais, $colIds);
        if (!empty($paraRemover)) {
            $this->model->where('gst_set_id', $setId)->whereIn('gst_col_id', $paraRemover)->update(['gst_ativo' => false]);
        }

        foreach ($colIds as $colId) {
            if (!in_array($colId, $atuais)) {
                $this->model->create([
                    'gst_set_id' => $setId,
                    'gst_col_id' => $colId,
                    'gst_ativo' => true,
                ]);
            } else {
                $this->model->where('gst_set_id', $setId)->where('gst_col_id', $colId)->update(['gst_ativo' => true]);
            }
        }
    }

    public function buscarGestoresDoSetor(int $setId): array
    {
        return $this->model
            ->where('gst_set_id', $setId)
            ->where('gst_ativo', true)
            ->pluck('gst_col_id')
            ->toArray();
    }

    public function buscarSetoresDoGestor(int $colId): array
    {
        return $this->model
            ->where('gst_col_id', $colId)
            ->where('gst_ativo', true)
            ->pluck('gst_set_id')
            ->toArray();
    }
}
