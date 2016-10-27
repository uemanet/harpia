<?php

namespace Modulos\Academico\Http\Controllers;

use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Academico\Http\Requests\ModuloMatrizRequest;
use Illuminate\Http\Request;
use Modulos\Academico\Repositories\ModuloMatrizRepository;
use Modulos\Academico\Repositories\ModuloDisciplinaRepository;
use Modulos\Academico\Repositories\MatrizCurricularRepository;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\DisciplinaRepository;

class ModulosMatrizesController extends BaseController
{
    protected $modulomatrizRepository;
    protected $modulodisciplinaRepository;
    protected $matrizcurricularRepository;
    protected $cursoRepository;
    protected $disciplinaRepository;

    public function __construct(ModuloMatrizRepository $modulomatrizRepository, ModuloDisciplinaRepository $modulodisciplinaRepository, MatrizCurricularRepository $matrizcurricularRepository, CursoRepository $cursoRepository, DisciplinaRepository $disciplinaRepository)
    {
        $this->modulomatrizRepository = $modulomatrizRepository;
        $this->modulodisciplinaRepository = $modulodisciplinaRepository;
        $this->matrizcurricularRepository = $matrizcurricularRepository;
        $this->cursoRepository = $cursoRepository;
        $this->disciplinaRepository = $disciplinaRepository;
    }

    public function getIndex($matrizId, Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setAction('/academico/modulosmatrizes/create/'.$matrizId)->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $matrizcurricular = $this->matrizcurricularRepository->find($matrizId);

        if (is_null($matrizcurricular)) {
            flash()->error('Matriz não existe!');
            return redirect()->back();
        }

        $curso = $this->cursoRepository->find($matrizcurricular->mtc_crs_id);

        $actionButtons[] = $btnNovo;

        $modulos = $this->modulomatrizRepository->getAllModulosByMatriz($matrizId);

        return view('Academico::modulosmatrizes.index', ['actionButton' => $actionButtons, 'matrizcurricular' => $matrizcurricular, 'curso' => $curso, 'modulos' => $modulos]);
    }

    public function getCreate($matrizId)
    {
        $matriz = $this->matrizcurricularRepository->listsAllById($matrizId);

        if ($matriz->isEmpty()) {
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

            if ($this->modulomatrizRepository->verifyNameMatriz($moduloNome, $idMatriz)) {
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

            flash()->error('Erro ao tentar atualizar. Caso o problema persista, entre em contato com o suporte.');
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

            if ($this->modulomatrizRepository->verifyNameMatriz($moduloNome, $idMatriz, $id)) {
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

            flash()->error('Erro ao tentar atualizar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function postDelete(Request $request)
    {
        try {
            $modulomatrizId = $request->get('id');

            $modulo = $this->modulomatrizRepository->find(($modulomatrizId));

            $disciplinas = $modulo->disciplinas()->get();

            if (!$disciplinas->isEmpty()) {
                flash()->error('Módulo tem disciplinas cadastradas, delete-as para excluir o módulo!');
                return redirect()->back();
            }


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

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getGerenciarDisciplinas($moduloId)
    {
        $disciplinas = $this->modulodisciplinaRepository->getAllDisciplinasByModulo($moduloId);

        $modulo = $this->modulomatrizRepository->find($moduloId);

        $matriz = $this->matrizcurricularRepository->find($modulo->mdo_mtc_id);

        $curso = $this->cursoRepository->find($matriz->mtc_crs_id);

        return view('Academico::modulosmatrizes.gerenciardisciplinas', ['modulo' => $moduloId,
                                                                        'disciplinas' => $disciplinas,
                                                                        'matriz' => $matriz->mtc_id,
                                                                        'moduloNome' => $modulo->mdo_nome,
                                                                        'matrizTitulo' => $matriz->mtc_titulo,
                                                                        'cursoNome' => $curso->crs_nome]);
    }
}
