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

    public function __construct(
        ModuloMatrizRepository $modulomatrizRepository,
        ModuloDisciplinaRepository $modulodisciplinaRepository,
        MatrizCurricularRepository $matrizcurricularRepository,
        CursoRepository $cursoRepository,
        DisciplinaRepository $disciplinaRepository
    ) {
        $this->modulomatrizRepository = $modulomatrizRepository;
        $this->modulodisciplinaRepository = $modulodisciplinaRepository;
        $this->matrizcurricularRepository = $matrizcurricularRepository;
        $this->cursoRepository = $cursoRepository;
        $this->disciplinaRepository = $disciplinaRepository;
    }

    public function getIndex($matrizId)
    {
        $matrizcurricular = $this->matrizcurricularRepository->find($matrizId);

        if (is_null($matrizcurricular)) {
            flash()->error('Matriz não existe!');
            return redirect()->back();
        }

        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setRoute('academico.cursos.matrizescurriculares.modulosmatrizes.create')
                ->setParameters(['id' => $matrizId])->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $curso = $this->cursoRepository->find($matrizcurricular->mtc_crs_id);

        $actionButtons[] = $btnNovo;

        $modulos = $this->modulomatrizRepository->getAllModulosByMatriz($matrizId);

        $disciplinas = [];

        // pega todas as disciplinas em conjunto com os seus pré-requisitos
        if ($modulos->count()) {
            foreach ($modulos as $modulo) {
                $disciplinas[$modulo->mdo_id] = $this->modulodisciplinaRepository->getAllDisciplinasByModulo($modulo->mdo_id);
            }
        }

        return view('Academico::modulosmatrizes.index', ['actionButton' => $actionButtons,
            'matrizcurricular' => $matrizcurricular, 'curso' => $curso, 'modulos' => $modulos,
            'disciplinas' => $disciplinas]);
    }

    public function getCreate(Request $request)
    {
        $matrizId = $request->get('id');

        $matriz = $this->matrizcurricularRepository->find($matrizId)->where('mtc_id', $matrizId)->pluck('mtc_titulo', 'mtc_id');

        if ($matriz->isEmpty()) {
            flash()->error('Matriz não existe!');
            return redirect()->back();
        }

        $curso = $this->cursoRepository->listsByMatrizId($matrizId);

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

            $dados = $request->all();
            $dados['mdo_cargahoraria_min_eletivas'] = ($request->input('mdo_cargahoraria_min_eletivas') == '') ? null : $request->input('mdo_cargahoraria_min_eletivas');
            $dados['mdo_creditos_min_eletivas'] = ($request->input('mdo_creditos_min_eletivas') == '') ? null : $request->input('mdo_creditos_min_eletivas');

            $modulomatriz = $this->modulomatrizRepository->create($dados);

            if (!$modulomatriz) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Módulo criado com sucesso.');
            return redirect()->route('academico.cursos.matrizescurriculares.modulosmatrizes.index', $modulomatriz->mdo_mtc_id);
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

        $curso = $this->cursoRepository->listsByMatrizId($modulo->mdo_mtc_id);

        $matriz = $this->matrizcurricularRepository->find($modulo->matriz->mtc_id)
                                                   ->where('mtc_id', $modulo->matriz->mtc_id)
                                                   ->pluck('mtc_titulo', 'mtc_id');


        return view('Academico::modulosmatrizes.edit', compact('matriz', 'curso', 'modulo'));
    }

    public function putEdit($id, ModuloMatrizRequest $request)
    {
        try {
            $modulo = $this->modulomatrizRepository->find($id);

            if (!$modulo) {
                flash()->error('Módulo não existe.');
                return redirect()->route('academico.cursos.matrizescurriculares.modulosmatrizes.index', $id);
            }

            $moduloNome = $request->input('mdo_nome');
            $idMatriz = $request->input('mdo_mtc_id');

            if ($this->modulomatrizRepository->verifyNameMatriz($moduloNome, $idMatriz, $id)) {
                $errors = array('mdo_nome' => 'Nome do Módulo já existe');
                return redirect()->back()->withInput($request->all())->withErrors($errors);
            }

            $requestData = $request->only($this->modulomatrizRepository->getFillableModelFields());
            $requestData['mdo_cargahoraria_min_eletivas'] = ($request->input('mdo_cargahoraria_min_eletivas') == '') ? null : $request->input('mdo_cargahoraria_min_eletivas');
            $requestData['mdo_creditos_min_eletivas'] = ($request->input('mdo_creditos_min_eletivas') == '') ? null : $request->input('mdo_creditos_min_eletivas');

            if (!$this->modulomatrizRepository->update($requestData, $modulo->mdo_id, 'mdo_id')) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Módulo atualizado com sucesso.');
            return redirect()->route('academico.cursos.matrizescurriculares.modulosmatrizes.index', $idMatriz);
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

            $modulo = $this->modulomatrizRepository->find($modulomatrizId);

            $disciplinas = $modulo->disciplinas;

            if ($disciplinas->count()) {
                flash()->error('Módulo tem disciplinas cadastradas, delete-as para excluir o módulo!');
                return redirect()->back();
            }

            $this->modulomatrizRepository->delete($modulomatrizId);
            flash()->success('Módulo excluído com sucesso.');

            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            flash()->error('Erro ao tentar deletar. O módulo contém dependências no sistema.');
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
        $modulo = $this->modulomatrizRepository->find($moduloId);

        if (!$modulo) {
            flash()->error('Módulo não existe.');
            return redirect()->back();
        }

        $disciplinas = $this->modulodisciplinaRepository->getAllDisciplinasByModulo($moduloId);

        $matriz = $this->matrizcurricularRepository->find($modulo->mdo_mtc_id);

        $curso = $this->cursoRepository->find($matriz->mtc_crs_id);

        return view('Academico::modulosmatrizes.gerenciardisciplinas', [
            'modulo' => $modulo,
            'disciplinas' => $disciplinas,
            'matriz' => $matriz,
            'curso' => $curso
        ]);
    }

    public function getEditarDisciplinas($id)
    {
        $moduloDisciplina = $this->modulodisciplinaRepository->find($id);

        if (!$moduloDisciplina) {
            flash()->error('Disciplina não existe.');
            return redirect()->back();
        }

        $modulo = $moduloDisciplina->modulo;
        $matriz = $modulo->matriz;
        $curso = $matriz->curso;

        // Tipos disciplina
        $tipos = [
            'obrigatoria' => 'Obrigatória',
            'optativa' => 'Optativa',
            'eletiva' => 'Eletiva',
            'tcc' => 'TCC'
        ];

        $disciplinasAnteriores = $this->disciplinaRepository->getDisciplinasModulosAnteriores($matriz->mtc_id, $modulo->mdo_id);
        $prerequisitos = collect($this->modulodisciplinaRepository->getDisciplinasPreRequisitos($moduloDisciplina->mdc_id))->pluck('mdc_id');

        $prerequisitosDisponiveis = $disciplinasAnteriores->mapWithKeys(function ($item) {
            return [$item->mdc_id => $item->dis_nome];
        });

        return view('Academico::modulosmatrizes.editardisciplina', [
            'disciplina' => $moduloDisciplina,
            'prerequisitosdisponiveis' => $prerequisitosDisponiveis,
            'prerequisitos' => $prerequisitos,
            'modulo' => $modulo,
            'matriz' => $matriz,
            'curso' => $curso,
            'tipos' => $tipos
        ]);
    }
}
