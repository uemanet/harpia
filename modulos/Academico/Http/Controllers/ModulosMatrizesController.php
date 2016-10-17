<?php

namespace Modulos\Academico\Http\Controllers;

use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Academico\Http\Requests\ModuloMatrizRequest;
use Illuminate\Http\Request;
use Modulos\Academico\Repositories\ModuloMatrizRepository;
use Modulos\Academico\Repositories\MatrizCurricularRepository;
use Modulos\Academico\Repositories\CursoRepository;

class ModulosMatrizesController extends BaseController
{
    protected $modulomatrizRepository;
    protected $matrizcurricularRepository;
    protected $cursoRepository;

    public function __construct(ModuloMatrizRepository $modulomatrizRepository, MatrizCurricularRepository $matrizcurricularRepository, CursoRepository $cursoRepository)
    {
        $this->modulomatrizRepository = $modulomatrizRepository;
        $this->matrizcurricularRepository = $matrizcurricularRepository;
        $this->cursoRepository = $cursoRepository;

    }

    public function getIndex($matrizId, Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setAction('/academico/modulosmatrizes/create/'.$matrizId)->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $matrizcurricular = $this->matrizcurricularRepository->find($matrizId);

        if (is_null($matrizcurricular)){
            flash()->error('Matriz não existe!');
            return redirect()->back();
        }

        $curso = $this->cursoRepository->find($matrizcurricular->mtc_crs_id);

        $actionButtons[] = $btnNovo;
        $paginacao = null;
        $tabela = null;

        $tableData = $this->modulomatrizRepository->paginateRequestByMatriz($matrizId, $request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'mdo_id' => '#',
                'mdo_nome' => 'Módulo',
                'mdo_action' => 'Ações'
            ))
                ->modifyCell('mdo_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('mdo_action', 'mdo_id')
                ->modify('mdo_action', function ($id) {
                    return ActionButton::grid([
                        'type' => 'SELECT',
                        'config' => [
                            'classButton' => 'btn-default',
                            'label' => 'Selecione'
                        ],
                        'buttons' => [
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-pencil',
                                'action' => '/academico/modulosmatrizes/edit/'.$id,
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'btn-delete text-red',
                                'icon' => 'fa fa-trash',
                                'action' => '/academico/modulosmatrizes/delete',
                                'id' => $id,
                                'label' => 'Excluir',
                                'method' => 'post'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('mdo_id', 'mdo_nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('Academico::modulosmatrizes.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons, 'matrizcurricular' => $matrizcurricular, 'curso' => $curso]);
    }

    public function getCreate($matrizId)
    {

        $matriz = $this->matrizcurricularRepository->listsAllById($matrizId);

        if ($matriz->isEmpty()){
            flash()->error('Matriz não existe!');
            return redirect()->back();
        }

        $curso = $this->cursoRepository->listsCursoByMatriz($matrizId);

        return view('Academico::modulosmatrizes.create', compact('matriz', 'curso'));
    }

    public function postCreate(ModuloMatrizRequest $request)
    {
        try {

            $moduloNome = $request->input('mdo_nome');
            $idMatriz = $request->input('mdo_mtc_id');

            if($this->modulomatrizRepository->verifyNameMatriz($moduloNome, $idMatriz))
            {
                $errors = array('mdo_nome' => 'Nome do Módulo já existe');
                return redirect()->back()->withInput($request->all())->withErrors($errors);
            }

            $modulomatriz = $this->modulomatrizRepository->create($request->all());

            if (!$modulomatriz) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Módulo criado com sucesso.');
            return redirect('/academico/modulosmatrizes/index/'.$modulomatriz->mdo_mtc_id);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->success('Erro ao tentar atualizar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getEdit($moduloId, Request $request)
    {
        $modulo = $this->modulomatrizRepository->find($moduloId);

        if (!$modulo) {
            flash()->error('Módulo não existe.');
            return redirect()->back();
        }

        $curso = $this->cursoRepository->listsCursoByMatriz($modulo->mdo_mtc_id);

        $matriz = $this->matrizcurricularRepository->listsAllById($modulo->mdo_mtc_id);


        return view('Academico::modulosmatrizes.edit', compact('matriz', 'curso', 'modulo'));
    }

    public function putEdit($id, ModuloMatrizRequest $request)
    {
        try {
            $modulo = $this->modulomatrizRepository->find($id);

            if (!$modulo) {
                flash()->error('Módulo não existe.');
                return redirect('/academico/modulosmatrizes/index/' . $id);
            }

            $moduloNome = $request->input('mdo_nome');
            $idMatriz = $request->input('mdo_mtc_id');

            if($this->modulomatrizRepository->verifyNameMatriz($moduloNome, $idMatriz, $id))
            {
                $errors = array('mdo_nome' => 'Nome do Módulo já existe');
                return redirect()->back()->withInput($request->all())->withErrors($errors);
            }

            $requestData = $request->only($this->modulomatrizRepository->getFillableModelFields());

            if (!$this->modulomatrizRepository->update($requestData, $modulo->mdo_id, 'mdo_id')) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Módulo atualizado com sucesso.');
            return redirect('/academico/modulosmatrizes/index/' . $modulo->mdo_mtc_id);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->success('Erro ao tentar atualizar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function postDelete(Request $request)
    {
        try {
            $modulomatrizId = $request->get('id');

            if ($this->modulomatrizRepository->delete($modulomatrizId)) {
                flash()->success('Módulo excluído com sucesso.');
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
