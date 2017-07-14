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
                'pes_id' => '#',
                'pes_nome' => 'Nome',
                'liv_tipo_livro' => 'Tipo de registro',
                'reg_action' => 'Ações',
            ))->modifyCell('reg_action', function () {
                return array('style' => 'width: 140px;');
            })->means('reg_action', 'reg_id')
                ->modify('reg_action', function ($id) {
                    return ActionButton::grid([
                        'type' => 'SELECT',
                        'config' => [
                            'classButton' => 'btn-default',
                            'label' => 'Selecione'
                        ],
                        'buttons' => [
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-eye',
                                'route' => 'academico.controlederegistro.show',
                                'parameters' => ['id' => $id],
                                'label' => 'Visualizar',
                                'method' => 'get'
                            ],
                        ]
                    ]);
                })->sortable(array('pes_id', 'pes_nome', 'liv_tipo_livro'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('Academico::controlederegistro.index', ['tabela' => $tabela, 'paginacao' => $paginacao]);
    }

    public function getShow($id)
    {
        if ($this->registroRepository->find($id)) {
            $registro = $this->registroRepository->detalhesDoRegistro($id);
            return view('Academico::controlederegistro.show', [
                'registro' => $registro
            ]);
        };
    }
}
