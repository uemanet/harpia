<?php

namespace Modulos\Academico\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Academico\Http\Requests\ListaSemturRequest;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\ListaSemturRepository;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;

class ListaSemturController extends BaseController
{
    protected $listaSemturRepository;
    protected $cursoRepository;

    public function __construct(ListaSemturRepository $listaSemturRepository, CursoRepository $cursoRepository)
    {
        $this->listaSemturRepository = $listaSemturRepository;
        $this->cursoRepository = $cursoRepository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setRoute('academico.carteirasestudantis.create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButton[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->listaSemturRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'lst_id' => '#',
                'lst_nome' => 'Nome',
                'lst_descricao' => 'Descricao',
                'lst_data_bloqueio' => 'Data do Bloqueio',
                'lst_action' => 'Ações',
            ))
            ->modifyCell('lst_action', function () {
                return array('style' => 'width: 15%;');
            })
            ->modify('lst_action', function ($obj) {
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
                            'route' => 'academico.carteirasestudantis.edit',
                            'parameters' => ['id' => $obj->lst_id],
                            'label' => 'Editar',
                            'method' => 'get'
                        ],
                        [
                            'classButton' => 'btn-delete text-red',
                            'icon' => 'fa fa-trash',
                            'route' => 'academico.carteirasestudantis.delete',
                            'id' => $obj->lst_id,
                            'label' => 'Excluir',
                            'method' => 'post'
                        ],
                        [
                            'classButton' => '',
                            'icon' => 'fa fa-eye',
                            'route' => 'academico.carteirasestudantis.showmatriculas',
                            'parameters' => ['id' => $obj->lst_id],
                            'label' => 'Gerenciar Matrículas',
                            'method' => 'get'
                        ],
                    ]
                ]);
            })
            ->sortable(array('lst_id', 'lst_nome', 'lst_data_bloqueio'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('Academico::carteirasestudantis.index', compact('tabela', 'paginacao', 'actionButton'));
    }

    public function getCreate()
    {
        return view('Academico::carteirasestudantis.create');
    }

    /**
     * @param ListaSemturRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function postCreate(ListaSemturRequest $request)
    {
        try {
            $this->listaSemturRepository->create($request->all());

            flash()->success('Lista criada com sucesso.');

            return redirect()->route('academico.carteirasestudantis.index');
        } catch (\Exception $e) {

            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Entra em contato com o Administrador');
            return redirect()->back();
        }
    }

    /**
     * @param int $id - ID da lista
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getEdit($id)
    {
        $lista = $this->listaSemturRepository->find($id);

        if (!$lista) {
            flash()->error('Lista de Carteiras de Estudantes não encontrada.');
            return redirect()->back();
        }

        return view('Academico::carteirasestudantis.edit', compact('lista'));
    }

    public function postEdit(ListaSemturRequest $request)
    {
        $lista = $this->listaSemturRepository->find($request->id);

        if (!$lista) {
            flash()->error('Lista de Carteiras de Estudantes não encontrada.');
            return redirect()->back();
        }

        try {
            $lista->fill($request->all())->save();

            flash()->success('Lista atualizada com sucesso.');

            return redirect()->route('academico.carteirasestudantis.index');
        } catch (\Exception $e) {

            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Entra em contato com o Administrador');
            return redirect()->back();
        }

    }

    public function postDelete(Request $request)
    {
        try {
            $id = $request->get('id');

            $this->listaSemturRepository->delete($id);

            flash()->success('Lista excluída com sucesso.');

            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            flash()->error('Erro ao tentar deletar. A lista contém dependências no sistema.');
            return redirect()->back();
        } catch (\Exception $e) {

            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar excluir. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getShowMatriculas(Request $request)
    {
        $lista = $this->listaSemturRepository->find($request->id);

        if (!$lista) {
            flash()->error('Lista de Carteiras de Estudantes não encontrada.');
            return redirect()->back();
        }

        $btnNovo = new TButton();
        $btnNovo->setName('Adicionar Matrículas')->setRoute('academico.carteirasestudantis.addmatriculas')->setParameters(['id' => $lista->lst_id])->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButton[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $parameters = $request->all();
        $parameters['lst_id'] = $lista->lst_id;
        $tableData = $this->listaSemturRepository->paginateRequest($parameters);

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'mat_id' => '#',
                'pes_nome' => 'Aluno',
                'trm_nome' => 'Turma',
                'pol_nome' => 'Polo',
                'lst_action' => 'Ações',
            ))
            ->modifyCell('lst_action', function () {
                return array('style' => 'width: 15%;');
            })
            ->modify('lst_action', function ($obj) {
                return ActionButton::grid([
                    'type' => 'SELECT',
                    'config' => [
                        'classButton' => 'btn-default',
                        'label' => 'Selecione'
                    ],
                    'buttons' => [
//                        [
//                            'classButton' => '',
//                            'icon' => 'fa fa-pencil',
//                            'route' => 'academico.carteirasestudantis.edit',
//                            'parameters' => ['id' => $obj->lst_id],
//                            'label' => 'Editar',
//                            'method' => 'get'
//                        ],
//                        [
//                            'classButton' => 'btn-delete text-red',
//                            'icon' => 'fa fa-trash',
//                            'route' => 'academico.carteirasestudantis.delete',
//                            'id' => $obj->lst_id,
//                            'label' => 'Excluir',
//                            'method' => 'post'
//                        ],
//                        [
//                            'classButton' => '',
//                            'icon' => 'fa fa-eye',
//                            'route' => 'academico.carteirasestudantis.showmatriculas',
//                            'parameters' => ['id' => $obj->lst_id],
//                            'label' => 'Gerenciar Matrículas',
//                            'method' => 'get'
//                        ],
                    ]
                ]);
            })
            ->sortable(array('mat_id', 'pes_nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        $turmas = $this->listaSemturRepository->getTurmasByLista($lista->lst_id);
        $polos = $this->listaSemturRepository->getPolosByLista($lista->lst_id);

        return view('Academico::carteirasestudantis.show', compact('lista', 'tabela', 'paginacao','actionButton', 'turmas', 'polos'));
    }

    public function getAddMatriculas($id)
    {
        $lista = $this->listaSemturRepository->find($id);

        if (!$lista) {
            flash()->error('Lista de Carteiras de Estudantes não encontrada.');
            return redirect()->back();
        }

        $cursos = $this->cursoRepository->lists('crs_id', 'crs_nome');

        return view('Academico::carteirasestudantis.addmatriculas', compact('lista', 'cursos'));

    }
}