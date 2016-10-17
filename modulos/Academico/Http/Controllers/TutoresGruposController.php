<?php

namespace Modulos\Academico\Http\Controllers;

use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Academico\Http\Requests\TutorGrupoRequest;
use Illuminate\Http\Request;
use Modulos\Academico\Repositories\TutorGrupoRepository;
use Modulos\Academico\Repositories\GrupoRepository;
use Modulos\Academico\Repositories\TutorRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Academico\Repositories\OfertaCursoRepository;

class TutoresGruposController extends BaseController
{
    protected $tutorgrupoRepository;
    protected $grupoRepository;
    protected $tutorRepository;
    protected $ofertacursoRepository;

    public function __construct(TutorGrupoRepository $tutorgrupoRepository, GrupoRepository $grupoRepository, TutorRepository $tutorRepository, TurmaRepository $turmaRepository, OfertaCursoRepository $ofertacursoRepository)
    {
        $this->tutorgrupoRepository = $tutorgrupoRepository;
        $this->grupoRepository = $grupoRepository;
        $this->tutorRepository = $tutorRepository;
        $this->turmaRepository = $turmaRepository;
        $this->ofertacursoRepository = $ofertacursoRepository;

    }

    public function getIndex($grupoId,Request $request)
    {
        $btnNovo = new TButton();

        if($this->tutorgrupoRepository->verifyTutorExists($grupoId))
        {
            $btnNovo->setName('Vincular Tutor')->setAction('/academico/tutoresgrupos/create/'. $grupoId)->setIcon('fa fa-paperclip')->setStyle('btn bg-blue');
        }

        $grupo = $this->grupoRepository->find($grupoId);

        if (is_null($grupo)){
            flash()->error('Grupo não existe!');
            return redirect()->back();
        }

        $turma = $this->turmaRepository->find($grupo->grp_trm_id);

        $oferta = $this->ofertacursoRepository->find($turma->trm_ofc_id);

        $actionButtons[] = $btnNovo;

        $tabela = null;
        $paginacao = null;

        $tableData = $this->tutorgrupoRepository->paginateRequestByGrupo($grupoId, $request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'ttg_id' => '#',
                'ttg_tut_id' => 'Tutor',
                'ttg_tipo_tutoria' => 'Tipo de Tutoria',
                'ttg_data_inicio' => 'Data de admissão',
                'ttg_action' => 'Ações'
            ))
                ->modifyCell('ttg_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('ttg_action', 'ttg_id')
                ->means('ttg_tut_id', 'tutor')
                ->modify('ttg_tut_id', function ($tutor) {
                    return $tutor->pessoa->pes_nome;
                })
                ->modify('ttg_action', function ($id) {
                    return ActionButton::grid([
                        'type' => 'SELECT',
                        'config' => [
                            'classButton' => 'btn-default',
                            'label' => 'Selecione'
                        ],
                        'buttons' => [
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-user',
                                'action' => '/academico/tutoresgrupos/alterartutor/' . $id,
                                'label' => 'Alterar Tutor',
                                'method' => 'get'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('ttg_id', 'ttg_tut_id'));

            $paginacao = $tableData->appends($request->except('page'));
        }
        return view('Academico::tutoresgrupos.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons, 'grupo' => $grupo, 'turma' => $turma, 'oferta' => $oferta]);
    }

    public function getCreate($grupoId)
    {
        $grupo = $this->grupoRepository->find($grupoId);

        if (is_null($grupo)){
            flash()->error('Grupo não existe!');
            return redirect()->back();
        }

        $presencial = $this->tutorgrupoRepository->verifyTutorPresencial("presencial", $grupoId);

        $distancia  = $this->tutorgrupoRepository->verifyTutorDistancia("distancia", $grupoId);

        if (!is_null($presencial) && !is_null($distancia)){
            flash()->error('O grupo já tem um tutor presencial e um tutor à distância!');
            return redirect()->back();
        }
        //dd($distancia, $presencial);

        $turma = $this->turmaRepository->find($grupo->grp_trm_id);

        $oferta = $this->ofertacursoRepository->listsAllById($turma->trm_ofc_id);

        $tutores = $this->tutorRepository->listsTutorPessoa();

        $turma = $this->turmaRepository->listsAllById($grupo->grp_trm_id);

        $grupo = $this->grupoRepository->listsAllById($grupoId);

        return view('Academico::tutoresgrupos.create', ['turma' => $turma, 'oferta' => $oferta, 'grupo' => $grupo, 'tutores' => $tutores, 'presencial' => $presencial, 'distancia' => $distancia]);
    }

    public function postCreate(TutorGrupoRequest $request)
    {

        try {

            $tipoTutoria = $request->input('ttg_tipo_tutoria');
            $idTutorGrupo = $request->input('ttg_id');
            $grupoTutor = $request->input('ttg_grp_id');

            if($this->tutorgrupoRepository->verifyTutorPresencial($tipoTutoria, $grupoTutor))
            {
                $errors = array('ttg_tipo_tutoria' => 'Já existe um tutor presencial');
                return redirect()->back()->withInput($request->all())->withErrors($errors);
            }

            if($this->tutorgrupoRepository->verifyTutorDistancia($tipoTutoria, $grupoTutor))
            {
                $errors = array('ttg_tipo_tutoria' => 'Já existe um tutor à distância');
                return redirect()->back()->withInput($request->all())->withErrors($errors);
            }


            $tutorgrupo = $this->tutorgrupoRepository->create($request->all());

            if (!$tutorgrupo) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Vínculo criado com sucesso.');
            return redirect('/academico/tutoresgrupos/index/'.$tutorgrupo->ttg_grp_id);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->success('Erro ao tentar atualizar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getAlterarTutor($tutorgrupoId)
    {
        $tutorgrupo = $this->tutorgrupoRepository->find($tutorgrupoId);

        if (is_null($tutorgrupo)){
            flash()->error('Este registro não existe!');
            return redirect()->back();
        }

        if ($tutorgrupo->ttg_data_fim <> null){
            flash()->error('Este tutor já foi desligado do grupo!');
            return redirect()->back();
        }

        $grupo = $this->grupoRepository->find($tutorgrupo->ttg_grp_id);

        $turma = $this->turmaRepository->find($grupo->grp_trm_id);

        $oferta = $this->ofertacursoRepository->listsAllById($turma->trm_ofc_id);

        $tutores = $this->tutorRepository->listsTutorPessoa();

        $tutor = $this->tutorRepository->find($tutorgrupo->ttg_tut_id);

        $turma = $this->turmaRepository->listsAllById($grupo->grp_trm_id);

        $grupo = $this->grupoRepository->listsAllById($tutorgrupo->ttg_grp_id);

        return view('Academico::tutoresgrupos.alterartutor', ['tutor' => $tutor, 'tutorgrupo' => $tutorgrupo, 'turma' => $turma, 'oferta' => $oferta, 'grupo' => $grupo, 'tutores' => $tutores]);
    }

    public function putAlterarTutor( $idTutorGrupo , TutorGrupoRequest $request)
    {
      try {
          $tutorgrupo = $this->tutorgrupoRepository->find($idTutorGrupo);

          if (!$tutorgrupo) {
              flash()->error('Turma não existe.');
              return redirect('/academico/tutoresgrupos/index/' . $tutorgrupo->ttg_grp_id);
          }

          $requestData = $request->only($this->tutorgrupoRepository->getFillableModelFields());

          //Atualiza o fim do vículo do tutor antigo
          $date = date('Y-m-d');
          $dados['ttg_data_fim'] = $date;
          $this->tutorgrupoRepository->update($dados, $tutorgrupo->ttg_id, 'ttg_id');


          $tutorgrupo = $this->tutorgrupoRepository->create($request->all());

          if (!$tutorgrupo) {
              flash()->error('Erro ao tentar salvar.');
              return redirect()->back()->withInput($request->all());
          }

          flash()->success('Tutor alterado com sucesso.');
          return redirect('/academico/tutoresgrupos/index/'.$tutorgrupo->ttg_grp_id);
      } catch (\Exception $e) {
          if (config('app.debug')) {
              throw $e;
          }

          flash()->success('Erro ao tentar atualizar. Caso o problema persista, entre em contato com o suporte.');
          return redirect()->back();
      }
    }
}
