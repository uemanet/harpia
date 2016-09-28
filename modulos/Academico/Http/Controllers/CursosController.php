<?php

namespace Modulos\Academico\Http\Controllers;

use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Academico\Http\Requests\CursoRequest;
use Illuminate\Http\Request;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\DepartamentoRepository;
use Modulos\Academico\Repositories\NivelCursoRepository;
use Modulos\Academico\Repositories\ProfessorRepository;

class CursosController extends BaseController
{
    protected $cursoRepository;
    protected $departamentoRepository;
    protected $nivelcursoRepository;
    protected $professorRepository;

    public function __construct(CursoRepository $curso,
                                DepartamentoRepository $departamento,
                                NivelCursoRepository $nivelcurso,
                                ProfessorRepository $professor)
    {
        $this->cursoRepository = $curso;
        $this->departamentoRepository = $departamento;
        $this->nivelcursoRepository = $nivelcurso;
        $this->professorRepository = $professor;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setAction('/academico/cursos/create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->cursoRepository->paginateRequest($request->all());
        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'crs_id' => '#',
                'crs_nome' => 'Curso',
                'crs_sigla' => 'Sigla',
                'crs_prf_diretor' => 'Diretor',
                'crs_descricao' => 'Descrição',
                'crs_action' => 'Ações',
            ))
                ->modifyCell('crs_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('crs_prf_diretor', 'diretor')
                ->modify('crs_prf_diretor', function ($diretor) {
                    return $diretor->pessoa->pes_nome;
                })
                ->means('crs_action', 'crs_id')
                ->modify('crs_action', function ($id) {
                    return ActionButton::grid([
                        'type' => 'SELECT',
                        'config' => [
                            'classButton' => 'btn-default',
                            'label' => 'Selecione'
                        ],
                        'buttons' => [
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-table',
                                'action' => '/academico/matrizescurriculares/index/' . $id,
                                'label' => 'Matrizes',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-pencil',
                                'action' => '/academico/cursos/edit/' . $id,
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'btn-delete text-red',
                                'icon' => 'fa fa-trash',
                                'action' => '/academico/cursos/delete',
                                'id' => $id,
                                'label' => 'Excluir',
                                'method' => 'post'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('crs_id', 'crs_nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }
        return view('Academico::cursos.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        $departamentos = $this->departamentoRepository->lists('dep_id', 'dep_nome');
        $niveiscursos = $this->nivelcursoRepository->lists('nvc_id', 'nvc_nome');
        $professores = $this->professorRepository->lists('prf_id', 'pes_nome');

        return view('Academico::cursos.create', ['departamentos' => $departamentos, 'niveiscursos' => $niveiscursos, 'professores' => $professores]);
    }

    public function postCreate(CursoRequest $request)
    {
        try {
            $curso = $this->cursoRepository->create($request->all());

            if (!$curso) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Curso criado com sucesso.');
            return redirect('/academico/cursos/index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->success('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getEdit($cursoId)
    {
        $curso = $this->cursoRepository->find($cursoId);
        $departamentos = $this->departamentoRepository->lists('dep_id', 'dep_nome');
        $niveiscursos = $this->nivelcursoRepository->lists('nvc_id', 'nvc_nome');
        $professores = $this->professorRepository->lists('prf_id', 'pes_nome');

        if (!$curso) {
            flash()->error('Curso não existe.');
            return redirect()->back();
        }

        return view('Academico::cursos.edit', ['curso' => $curso, 'departamentos' => $departamentos, 'niveiscursos' => $niveiscursos, 'professores' => $professores]);
    }

    public function putEdit($id, CursoRequest $request)
    {
        try {
            $curso = $this->cursoRepository->find($id);

            if (!$curso) {
                flash()->error('Curso não existe.');
                return redirect('/academico/cursos/index');
            }

            $requestData = $request->only($this->cursoRepository->getFillableModelFields());

            if (!$this->cursoRepository->update($requestData, $curso->crs_id, 'crs_id')) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Curso atualizado com sucesso.');
            return redirect('/academico/cursos/index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->success('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function postDelete(Request $request)
    {
        try {
            $cursoId = $request->get('id');

            if ($this->cursoRepository->delete($cursoId)) {
                flash()->success('Curso excluído com sucesso.');
            } else {
                flash()->error('Erro ao tentar excluir o módulo');
            }

            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->success('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }
}
