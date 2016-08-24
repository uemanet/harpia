<?php

namespace Modulos\Academico\Http\Controllers;

use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Illuminate\Http\Request;
use Modulos\Academico\Repositories\MatrizCurricularRepository;
use Modulos\Academico\Http\Requests\MatrizCurricularRequest;

class MatrizesCurricularesController extends BaseController
{
    protected $matrizCurricularRepository;
    protected $cursoRepository;

    public function __construct(MatrizCurricularRepository $matrizCurricularRepository, CursoRepository $cursoRepository)
    {
        $this->matrizCurricularRepository = $matrizCurricularRepository;
        $this->cursoRepository = $cursoRepository;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setAction('/academico/matrizescurriculares/create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $tableData = $this->matrizCurricularRepository->paginateRequest($request->all());

        $tabela = $tableData->columns(array(
            'mtc_id' => '#',
            'mtc_crs_id' => 'Curso',
            'mtc_creditos' => 'Créditos',
            'mtc_horas' => 'Horas',
            'mtc_horas_praticas' => 'Horas práticas',
            'mtc_action' => 'Ações'
        ))
            ->modifyCell('mtc_action', function () {
                return array('style' => 'width: 140px;');
            })
            ->means('mtc_action', 'mtc_id')
            ->modify('mtc_action', function ($id) {
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
                            'action' => '/academico/matrizescurriculares/edit/' . $id,
                            'label' => 'Editar',
                            'method' => 'get'
                        ],
                        [
                            'classButton' => 'btn-delete text-red',
                            'icon' => 'fa fa-trash',
                            'action' => '/academico/matrizescurriculares/delete',
                            'id' => $id,
                            'label' => 'Excluir',
                            'method' => 'post'
                        ]
                    ]
                ]);
            })
            ->sortable(array('mtc_id', 'mtc_crs_id'));

        $paginacao = $tableData->appends($request->except('page'));

        return view('Academico::matrizescurriculares.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        $cursos = $this->cursoRepository->lists('crs_id', 'crs_nome');

        return view('Academico::matrizescurriculares.create', ['cursos' => $cursos]);
    }

    public function postCreate(MatrizCurricularRequest $request)
    {
        try {
            $matrizCurricular = $this->matrizCurricularRepository->create($request->all());
            // TODO implementar tratamento de upload de arquivos
            if (!$matrizCurricular) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Matriz curricular criada com sucesso.');

            return redirect('/matrizescurriculares/polos');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->success('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }

    public function getEdit($matrizCurricularId)
    {
        $matrizCurricular = $this->matrizCurricularRepository->find($matrizCurricularId);

        if (!$matrizCurricular) {
            flash()->error('Matriz curricular não existe.');

            return redirect()->back();
        }

        $cursos = $this->cursoRepository->lists('crs_id', 'crs_nome');

        return view('Academico::matrizescurriculares.edit', ['matrizCurricular' => $matrizCurricular, 'cursos' => $cursos]);
    }

    public function putEdit($poloId, PoloRequest $request)
    {
        try {
            $polo = $this->poloRepository->find($poloId);

            if (!$polo) {
                flash()->error('Polo não existe.');

                return redirect('/academico/polos');
            }

            // TODO implementar tratamento de upload de arquivos
            $requestData = $request->only($this->poloRepository->getFillableModelFields());

            if (!$this->poloRepository->update($requestData, $polo->pol_id, 'pol_id')) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Polo atualizado com sucesso.');

            return redirect('/academico/polos');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->success('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }

    public function postDelete(Request $request)
    {
        try {
            $matrizCurricularId = $request->get('id');

            if ($this->matrizCurricularRepository->delete($matrizCurricularId)) {
                flash()->success('Matriz curricular excluída com sucesso.');
            } else {
                flash()->error('Erro ao tentar excluir a matriz curricular');
            }

            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->success('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }
}
