<?php

namespace Modulos\Academico\Http\Controllers\Async;

use Illuminate\Http\Request;
use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Core\Http\Controller\BaseController;

class Alunos extends BaseController
{
    protected $alunoRepository;

    public function __construct(AlunoRepository $aluno)
    {
        $this->alunoRepository = $aluno;
    }

    public function getFind(Request $request)
    {
        $cpf = $request->input('doc_conteudo');
        $nome = $request->input('pes_nome');
    }
}
