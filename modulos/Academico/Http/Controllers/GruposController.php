<?php
namespace Modulos\Academico\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Academico\Http\Requests\GrupoRequest;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\GrupoRepository;
use Modulos\Academico\Repositories\PoloRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Academico\Repositories\OfertaCursoRepository;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;

class GruposController extends BaseController
{
    protected $grupoRepository;
    protected $cursoRepository;
    protected $turmaRepository;
    protected $poloRepository;
    protected $ofertaCursoRepository;

    public function __construct(GrupoRepository $grupo, CursoRepository $curso, TurmaRepository $turma, PoloRepository $polo, OfertaCursoRepository $oferta)
    {
        $this->grupoRepository = $grupo;
        $this->cursoRepository = $curso;
        $this->turmaRepository = $turma;
        $this->poloRepository = $polo;
        $this->ofertaCursoRepository = $oferta;
    }

    public function getIndex($turmaId, Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setAction('/academico/grupos/create/'.$turmaId)->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $turma = $this->turmaRepository->find($turmaId);
        $oferta = $this->ofertaCursoRepository->find($turma->trm_ofc_id);

        $actionButtons[] = $btnNovo;

        $tabela = null;
        $paginacao = null;

        $tableData = $this->grupoRepository->paginateRequestByTurma($turmaId, $request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'grp_id' => '#',
                'grp_nome' => 'Grupo',
                'grp_pol_id' => 'Polo',
                'grp_action' => 'Ações'
            ))
                ->modifyCell('grp_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('grp_action', 'grp_id')
                ->means('grp_pol_id', 'polo.pol_nome')
                ->modify('grp_action', function ($id) {
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
                                'action' => '/academico/grupos/edit/' . $id,
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'btn-delete text-red',
                                'icon' => 'fa fa-trash',
                                'action' => '/academico/grupos/delete',
                                'id' => $id,
                                'label' => 'Excluir',
                                'method' => 'post'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('grp_id', 'grp_nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('Academico::grupos.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons, 'turma' => $turma, 'oferta' => $oferta]);
    }

    public function getCreate($turmaId)
    {
        $turma = $this->turmaRepository->find($turmaId);

        $oferta = $this->ofertaCursoRepository->find($turma->trm_ofc_id);

        $curso = $this->cursoRepository->listsCursoByOferta($oferta->ofc_crs_id);

        $polos = $this->poloRepository->findAllByOfertaCurso($oferta->ofc_crs_id);

        $oferta = $this->ofertaCursoRepository->listsOfertaByTurma($turma->trm_ofc_id);

        $turma = $this->turmaRepository->listsAllById($turmaId);



        //dd($oferta,$curso,$turma,$polos);
        return view('Academico::grupos.create', ['curso' => $curso, 'oferta' => $oferta, 'turma' => $turma, 'polos' => $polos]);
    }

    public function postCreate(GrupoRequest $request)
    {
        try {
            $grupo = $this->grupoRepository->create($request->all());

            if (!$grupo) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Grupo criado com sucesso.');

            return redirect('/academico/grupos/index/'.$grupo->grp_trm_id);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->success('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }

    public function getEdit($grupoId)
    {
        $grupo = $this->grupoRepository->find($grupoId);

        $turma = $this->turmaRepository->find($grupo->grp_trm_id);

        $oferta = $this->ofertaCursoRepository->find($turma->trm_ofc_id);

        $curso = $this->cursoRepository->listsCursoByOferta($oferta->ofc_crs_id);

        $polos = $this->poloRepository->findAllByOfertaCurso($oferta->ofc_crs_id);

        $oferta = $this->ofertaCursoRepository->listsOfertaByTurma($turma->trm_ofc_id);

        $turma = $this->turmaRepository->listsAllById($grupo->grp_trm_id);



        //dd($oferta,$curso,$turma,$polos);


        if (!$grupo) {
            flash()->error('Grupo não existe.');

            return redirect()->back();
        }

        return view('Academico::grupos.edit', ['grupo' => $grupo, 'curso' => $curso, 'oferta' => $oferta, 'turma' => $turma, 'polos' => $polos]);
    }

    public function putEdit($id, GrupoRequest $request)
    {
        try {
            $grupo = $this->grupoRepository->find($id);

            if (!$grupo) {
                flash()->error('Grupo não existe.');

                return redirect('/academico/grupos');
            }

            $requestData = $request->only($this->grupoRepository->getFillableModelFields());

            if (!$this->grupoRepository->update($requestData, $grupo->grp_id, 'grp_id')) {
                flash()->error('Erro ao tentar editar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Grupo atualizado com sucesso.');

            return redirect('/academico/grupos/index/'.$grupo->grp_trm_id);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->success('Erro ao tentar editar. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }

    public function postDelete(Request $request)
    {
        try {
            $grupoId = $request->get('id');

            if ($this->grupoRepository->delete($grupoId)) {
                flash()->success('Grupo excluído com sucesso.');
            } else {
                flash()->error('Erro ao tentar excluir o módulo');
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

    private function lists($array)
    {
        $result = [];
        if (count($array)) {
            foreach ($array as $obj) {
                $element = [];
                foreach ($obj as $key => $value) {
                    $element[] = $value;
                }

                $result[$element[0]] = $element[1];
            }
        }

        return collect($result);
    }
}
