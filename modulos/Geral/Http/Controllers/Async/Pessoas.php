<?php

namespace Modulos\Geral\Http\Controllers\Async;

use Illuminate\Http\JsonResponse;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Geral\Repositories\PessoaRepository;

class Pessoas extends BaseController
{
    protected $pessoaRepository;

    public function __construct(PessoaRepository $pessoaRepository)
    {
        $this->pessoaRepository = $pessoaRepository;
    }

    public function getVerificapessoa($cpf)
    {
        $pessoa = $this->pessoaRepository->findPessoaByCpf($cpf);
        return new JsonResponse($pessoa, 200);
    }
}