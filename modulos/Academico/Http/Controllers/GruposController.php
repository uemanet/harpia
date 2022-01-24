<?php

namespace Modulos\Academico\Http\Controllers;

use Illuminate\Http\Request;
use Modulos\Academico\Events\UpdateGrupoEvent;
use Modulos\Academico\Events\DeleteGrupoEvent;
use Modulos\Academico\Events\CreateGrupoEvent;
use Modulos\Academico\Http\Requests\GrupoRequest;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Integracao\Repositories\AmbienteVirtualRepository;
use Modulos\Academico\Repositories\GrupoRepository;
use Modulos\Academico\Repositories\PoloRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Academico\Repositories\OfertaCursoRepository;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use DB;

class GruposController extends BaseController
{
    protected $grupoRepository;
    protected $cursoRepository;
    protected $turmaRepository;
    protected $poloRepository;
    protected $ofertaCursoRepository;
    protected $ambienteRepository;

    public function __construct(
        GrupoRepository $grupo,
        CursoRepository $curso,
        TurmaRepository $turma,
        PoloRepository $polo,
        OfertaCursoRepository $oferta,
        AmbienteVirtualRepository $ambienteRepository
    ) {
        $this->grupoRepository = $grupo;
        $this->cursoRepository = $curso;
        $this->turmaRepository = $turma;
        $this->poloRepository = $polo;
        $this->ofertaCursoRepository = $oferta;
        $this->ambienteRepository = $ambienteRepository;
    }

    public function getIndex($turmaId, Request $request)
    {
        $turma = $this->turmaRepository->find($turmaId);
        if (!$turma) {
            flash()->error('Turma não existe');

            return redirect()->back();
        }

        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setRoute('academico.ofertascursos.turmas.grupos.create')->setParameters(['id' => $turmaId])->setIcon('fa fa-plus')->setStyle('btn bg-olive');

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
                                'icon' => 'fa fa-exchange',
                                'route' => 'academico.ofertascursos.turmas.grupos.movimentacoes',
                                'parameters' => ['id' => $id],
                                'label' => 'Histórico',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-user',
                                'route' => 'academico.ofertascursos.turmas.grupos.tutoresgrupos.index',
                                'parameters' => ['id' => $id],
                                'label' => 'Tutores',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-pencil',
                                'route' => 'academico.ofertascursos.turmas.grupos.edit',
                                'parameters' => ['id' => $id],
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'btn-delete text-red',
                                'icon' => 'fa fa-trash',
                                'route' => 'academico.ofertascursos.turmas.grupos.delete',
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

    public function getCreate(Request $request)
    {
        $turmaId = $request->get('id');

        $turma = $this->turmaRepository->find($turmaId);

        if (!$turma) {
            flash()->error('Turma não existe');

            return redirect()->back();
        }

        $oferta = $turma->ofertacurso;
        $curso = $oferta->curso->where('crs_id', $turma->ofertacurso->curso->crs_id)->pluck('crs_nome', 'crs_id');
        $polos = $turma->ofertacurso->polos->pluck('pol_nome', 'pol_id');
        $oferta = $oferta->where('ofc_id', $oferta->ofc_id)->pluck('ofc_ano', 'ofc_id');
        $turma = $this->turmaRepository->listsAllById($turmaId);

        return view('Academico::grupos.create', ['curso' => $curso, 'oferta' => $oferta, 'turma' => $turma, 'polos' => $polos]);
    }

    public function postCreate(GrupoRequest $request)
    {
        try {
            $grupoNome = $request->input('grp_nome');
            $idTurma = $request->input('grp_trm_id');

            if ($this->grupoRepository->verifyNameGrupo($grupoNome, $idTurma)) {
                $errors = array('grp_nome' => 'Nome do Grupo já existe para essa turma');
                return redirect()->back()->withInput($request->all())->withErrors($errors);
            }

            $grupo = $this->grupoRepository->create($request->all());

            if (!$grupo) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            // busca a turma vinculado ao grupo
            $turma = $this->turmaRepository->find($grupo->grp_trm_id);

            if ($turma->trm_integrada) {
                event(new CreateGrupoEvent($grupo, null, $turma->trm_tipo_integracao));
            }

            flash()->success('Grupo criado com sucesso.');
            return redirect()->route('academico.ofertascursos.turmas.grupos.index', $grupo->grp_trm_id);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getEdit($grupoId)
    {
        $grupo = $this->grupoRepository->find($grupoId);

        if (!$grupo) {
            flash()->error('Grupo não existe.');
            return redirect()->back();
        }

        $turma = $this->turmaRepository->find($grupo->grp_trm_id);
        $oferta = $this->ofertaCursoRepository->find($turma->trm_ofc_id);
        $curso = $oferta->curso->where('crs_id', $turma->ofertacurso->curso->crs_id)->pluck('crs_nome', 'crs_id');
        $polos = $oferta->polos->pluck('pol_nome', 'pol_id');
        $oferta = $oferta->where('ofc_id', $oferta->ofc_id)->pluck('ofc_ano', 'ofc_id');
        $turma = $this->turmaRepository->listsAllById($grupo->grp_trm_id);


        return view('Academico::grupos.edit', [
            'grupo' => $grupo,
            'curso' => $curso,
            'oferta' => $oferta,
            'turma' => $turma,
            'polos' => $polos
        ]);
    }

    public function putEdit($id, GrupoRequest $request)
    {
        try {
            $grupo = $this->grupoRepository->find($id);
            $grupoNome = $request->input('grp_nome');
            $idTurma = $request->input('grp_trm_id');

            if (!$grupo) {
                flash()->error('Grupo não existe.');
                return redirect()->back();
            }

            if ($this->grupoRepository->verifyNameGrupo($grupoNome, $idTurma, $id)) {
                $errors = array('grp_nome' => 'Nome do Grupo já existe para essa turma');
                return redirect()->back()->withInput($request->all())->withErrors($errors);
            }

            $requestData = $request->only($this->grupoRepository->getFillableModelFields());

            if (!$this->grupoRepository->update($requestData, $grupo->grp_id, 'grp_id')) {
                flash()->error('Erro ao tentar editar.');
                return redirect()->back()->withInput($request->all());
            }

            // busca a turma vinculado ao grupo
            $turma = $this->turmaRepository->find($grupo->grp_trm_id);

            if ($turma->trm_integrada) {
                $grupoAtt = $this->grupoRepository->find($id);
                event(new UpdateGrupoEvent($grupoAtt, null, $turma->trm_tipo_integracao));
            }

            flash()->success('Grupo atualizado com sucesso.');
            return redirect()->route('academico.ofertascursos.turmas.grupos.index', $grupo->grp_trm_id);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function postDelete(Request $request)
    {
        try {
            $grupoId = $request->get('id');

            $grupo = $this->grupoRepository->find($grupoId);

            $turmaId = $grupo->grp_trm_id;

            DB::beginTransaction();

            $this->grupoRepository->delete($grupoId);

            $ambiente = $this->ambienteRepository->getAmbienteByTurma($turmaId);

            if ($ambiente) {
                event(new DeleteGrupoEvent($grupo, $ambiente->amb_id, $grupo->turma->trm_tipo_integracao));
            }

            flash()->success('Grupo excluído com sucesso.');

            DB::commit();

            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            flash()->error('Erro ao tentar deletar. O grupo contém dependências no sistema.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();

            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar excluir. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getMovimentacoes($id)
    {
        $grupo = $this->grupoRepository->find($id);

        if (!$grupo) {
            flash()->error('Grupo não existe.');
            return redirect()->back();
        }

        return view('Academico::grupos.movimentacoes', [
            'grupo' => $grupo,
            'movimentacoes' => $this->grupoRepository->getMovimentacoes($grupo->grp_id)
        ]);
    }
}
