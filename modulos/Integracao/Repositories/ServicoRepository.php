<?php
declare(strict_types=1);

namespace Modulos\Integracao\Repositories;

use DB;
use Modulos\Integracao\Models\Servico;
use Modulos\Core\Repository\BaseRepository;

class ServicoRepository extends BaseRepository
{
    protected $ambienteVirtualRepository;

    public function __construct(Servico $servico, AmbienteVirtualRepository $ambienteVirtualRepository)
    {
        parent::__construct($servico);
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function verifyIfExistsAmbienteServico($ambienteId, $servicoId) : bool
    {
        $ambiente = $this->ambienteVirtualRepository->find($ambienteId);

        $temServico = $ambiente->servicos->filter(function ($value) use ($servicoId) {
            if ($value->ser_id == $servicoId) {
                return $value;
            }
        });

        if ($temServico->count()) {
            return true;
        }

        return false;
    }
}
