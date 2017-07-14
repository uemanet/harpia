<?php

namespace Modulos\Academico\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Academico\Repositories\LivroRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Academico\Repositories\RegistroRepository;
use ActionButton;

class ControleRegistroController
{
    protected $livroRepository;
    protected $registroRepository;
    protected $matriculaCursoRepository;

    public function __construct(LivroRepository $livroRepository,
                                RegistroRepository $registroRepository,
                                MatriculaCursoRepository $matriculaCursoRepository)
    {
        $this->livroRepository = $livroRepository;
        $this->registroRepository = $registroRepository;
        $this->matriculaCursoRepository = $matriculaCursoRepository;
    }

    public function getIndex(Request $request)
    {
        $actionButtons[] = null;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->registroRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'reg_id' => '#',
                'reg_mat_id' => 'Matrícula',
                'reg_liv_id' => 'Livro',
            ))->sortable(array('reg_id', 'reg_mat_id'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('Academico::controlederegistro.index', ['tabela' => $tabela, 'paginacao' => $paginacao]);
    }
}
