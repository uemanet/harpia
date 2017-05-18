<?php
namespace Modulos\Academico\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Academico\Http\Requests\CentroRequest;
use Modulos\Academico\Repositories\CentroRepository;
use Modulos\Academico\Repositories\ProfessorRepository;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;

class CentrosController extends BaseController
{
    protected $centroRepository;
    protected $professorRepository;

    public function __construct(CentroRepository $centro, ProfessorRepository $professor)
    {
        $this->centroRepository = $centro;
        $this->professorRepository = $professor;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setRoute('academico.centros.create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->centroRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'cen_id' => '#',
                'cen_nome' => 'Centro',
                'cen_sigla' => 'Sigla',
                'cen_prf_diretor' => 'Diretor',
                'cen_action' => 'Ações'
            ))
                ->modifyCell('cen_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('cen_action', 'cen_id')
                ->means('cen_prf_diretor', 'diretor')
                ->modify('cen_prf_diretor', function ($diretor) {
                    return $diretor->pessoa->pes_nome;
                })
                ->modify('cen_action', function ($id) {
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
                                'route' => 'academico.centros.edit',
                                'parameters' => ['id' => $id],
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'btn-delete text-red',
                                'icon' => 'fa fa-trash',
                                'route' => 'academico.centros.delete',
                                'id' => $id,
                                'label' => 'Excluir',
                                'method' => 'post'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('cen_id', 'cen_nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }


        return view('Academico::centros.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        $professores = $this->professorRepository->lists('prf_id', 'pes_nome');
        return view('Academico::centros.create', compact('professores'));
    }

    public function postCreate(CentroRequest $request)
    {
        try {
            $centro = $this->centroRepository->create($request->all());

            if (!$centro) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Centro criado com sucesso.');

            return redirect()->route('academico.centros.index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getEdit($centroId)
    {
        $centro = $this->centroRepository->find($centroId);

        if (is_null($centro)) {
            flash()->error('Centro não existe!');
            return redirect()->back();
        }

        $professores = $this->professorRepository->listsEditCentro('prf_id', 'pes_nome', $centroId);

        return view('Academico::centros.edit', ['centro' => $centro, 'professores' => $professores]);
    }

    public function putEdit($id, CentroRequest $request)
    {
        try {
            $centro = $this->centroRepository->find($id);

            if (!$centro) {
                flash()->error('Centro não existe.');
                return redirect('/academico/centros/index');
            }

            $requestData = $request->only($this->centroRepository->getFillableModelFields());

            if (!$this->centroRepository->update($requestData, $centro->cen_id, 'cen_id')) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Centro atualizado com sucesso.');

            return redirect()->route('academico.centros.index');
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
            $centroId = $request->get('id');

            if ($this->centroRepository->delete($centroId)) {
                flash()->success('Centro excluído com sucesso.');
            } elseif (is_null($this->centroRepository->delete($centroId))) {
                flash()->error('Erro ao tentar excluir o centro. Há departamentos vinculados a ele');
            } else {
                flash()->error('Erro ao tentar excluir o centro');
            }

            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar deletar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }
}
