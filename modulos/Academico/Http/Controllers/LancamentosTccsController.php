<?php

namespace Modulos\Academico\Http\Controllers;

use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Academico\Http\Requests\LancamentoTccRequest;
use Illuminate\Http\Request;
use Modulos\Academico\Models\Matricula;
use Modulos\Academico\Repositories\LancamentoTccRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Academico\Repositories\ProfessorRepository;
use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Academico\Repositories\ModuloDisciplinaRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Geral\Repositories\AnexoRepository;

class LancamentosTccsController extends BaseController
{
    protected $lancamentotccRepository;
    protected $turmaRepository;
    protected $modulodisciplinaRepository;
    protected $professorRepository;
    protected $alunoRepository;
    protected $matriculacursoRepository;
    protected $anexoRepository;

    public function __construct(LancamentoTccRepository $lancamentotccRepository,
                                TurmaRepository $turmaRepository,
                                ModuloDisciplinaRepository $modulodisciplinaRepository,
                                ProfessorRepository $professorRepository,
                                AlunoRepository $alunoRepository,
                                MatriculaCursoRepository $matriculacursoRepository,
                                AnexoRepository $anexoRepository)
    {
        $this->lancamentotccRepository = $lancamentotccRepository;
        $this->turmaRepository = $turmaRepository;
        $this->modulodisciplinaRepository = $modulodisciplinaRepository;
        $this->professorRepository = $professorRepository;
        $this->alunoRepository = $alunoRepository;
        $this->matriculacursoRepository = $matriculacursoRepository;
        $this->anexoRepository = $anexoRepository;
    }

    public function getIndex(Request $request)
    {
        $paginacao = null;
        $tabela = null;

        $tableData = $this->modulodisciplinaRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'trm_id' => '#',
                'trm_nome' => 'Turma',
                'crs_nome' => 'Curso',
                'ofc_ano' => 'Ano da Oferta',
                'trm_action' => ''

            ))
                ->modifyCell('trm_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('trm_action', 'trm_id')

                ->modify('trm_action', function ($id) {
                    return  ActionButton::grid([
                        'type' => 'LINE',
                        'buttons' => [
                            [
                                'classButton' => 'btn btn-primary',
                                'icon' => 'fa fa-user',
                                'route' => 'academico.lancamentostccs.alunosturma',
                                'parameters' => ['id' => $id],
                                'label' => 'Alunos',
                                'method' => 'get'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('trm_id', 'trm_nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }
        return view('Academico::lancamentostccs.index', ['tabela' => $tabela, 'paginacao' => $paginacao]);
    }

    public function getAlunosTurma($turmaId)
    {
        $turma = $this->turmaRepository->find($turmaId);

        $dados = $this->matriculacursoRepository->findDadosByTurmaId($turmaId);
      
        if (!$turma) {
            flash()->error('Turma não existe!');
            return redirect()->back();
        }

        $disciplina = $this->lancamentotccRepository->findDisciplinaByTurma($turmaId);


        if ($disciplina === null) {
            flash()->error('Turma sem alunos matriculados em disciplina do tipo TCC');
            return redirect()->back();
        }

        return view('Academico::lancamentostccs.alunosturma', compact('turma', 'dados', 'disciplina'));
    }

    public function getCreate(Request $request)
    {
        $turmaId = $request->get('turma');
        $alunoId = $request->get('aluno');
        $matriculaoferta = $request->get('matriculaoferta');


        $turma = $this->turmaRepository->find($turmaId);

        if (!$turma) {
            flash()->error('Turma não existe!');
            return redirect()->back();
        }

        $aluno = $this->alunoRepository->find($alunoId);

        $matricula = $this->matriculacursoRepository->findMatriculaIdByTurmaAluno($alunoId, $turmaId);

        if (!$matricula) {
            flash()->error('Aluno não está matriculado na disciplina de TCC!');
            return redirect()->back();
        }

        if (!$aluno) {
            flash()->error('Aluno não existe!');
            return redirect()->back();
        }

        $professores = $this->professorRepository->lists('prf_id', 'pes_nome', true);

        $tiposdetcc = [
            'artigo' => 'Artigo',
            'monografia' => 'Monografia',
            'estudo_de_caso' => 'Estudo de Caso',
            'revisao_de_bibliografia' => 'Revisão de Bibliografia',
            'pesquisa_de_recepcao' => 'Pesquisa de Recepção',
            'projeto_arquitetonico_urbanistico' => 'Projeto Aquitetônico e Urbanistico',
            'plano_de_negocio' => 'Plano de Negócio'
        ];
        $disciplina = $this->lancamentotccRepository->findDisciplinaByTurma($turmaId);

        return view('Academico::lancamentostccs.create', compact('turma', 'aluno', 'professores', 'disciplina', 'tiposdetcc', 'matricula', 'matriculaoferta'));
    }

    public function getTccAnexo($lancamentotccId)
    {
        $lancamentotcc = $this->lancamentotccRepository->find($lancamentotccId);

        if (!$lancamentotcc) {
            flash()->error('Lançamento de Tcc não existe.');
            return redirect()->back();
        }

        $anexo =  $this->anexoRepository->recuperarAnexo($lancamentotcc->ltc_anx_tcc);

        if ($anexo == 'error_non_existent') {
            flash()->error('anexo não existe');
            return redirect()->back();
        }

        return $anexo;
    }

    public function postCreate(LancamentoTccRequest $request)
    {
        try {
            $dados = $request->except('trm_id');

            $turmaId = $request->input('trm_id');

            if ($request->file('ltc_file') != null) {
                $anexoDocumento = $request->file('ltc_file');
                $anexoCriado = $this->anexoRepository->salvarAnexo($anexoDocumento);

                if ($anexoCriado['type'] == 'error_exists') {
                    flash()->error($anexoCriado['message']);
                    return redirect()->back()->withInput($request->all());
                }

                if (!$anexoCriado) {
                    flash()->error('ocorreu um problema ao salvar o arquivo');
                    return redirect()->back()->withInput($request->all());
                }

                $dados['ltc_anx_tcc'] = $anexoCriado->anx_id;
            }

            unset($dados['ltc_file']);

            $lancamentotcc = $this->lancamentotccRepository->create($dados);

            if (!$lancamentotcc) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }



            flash()->success('Lançamento de TCC criado com sucesso.');
            return redirect()->route('academico.lancamentostccs.alunosturma', $turmaId);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar atualizar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getEdit($lancamentoTccId)
    {
        $lancamentoTcc = $this->lancamentotccRepository->find($lancamentoTccId);

        if (!$lancamentoTcc) {
            flash()->error('Este TCC não existe!');
            return redirect()->back();
        }

        $professores = $this->professorRepository->lists('prf_id', 'pes_nome', true);

        $tiposdetcc = [
            'artigo' => 'Artigo',
            'monografia' => 'Monografia',
            'estudo_de_caso' => 'Estudo de Caso',
            'revisao_de_bibliografia' => 'Revisão de Bibliografia',
            'pesquisa_de_recepcao' => 'Pesquisa de Recepção',
            'projeto_arquitetonico_urbanistico' => 'Projeto Aquitetônico e Urbanistico',
            'plano_de_negocio' => 'Plano de Negócio'
        ];

        $anexo = $this->anexoRepository->find($lancamentoTcc->ltc_anx_tcc);

        $disciplina = $this->lancamentotccRepository->findDisciplinaByTurma($lancamentoTcc->matriculaOferta->matriculaCurso->turma->trm_id);

        return view('Academico::lancamentostccs.edit', compact('lancamentoTcc', 'turma', 'aluno', 'professores', 'disciplina', 'tiposdetcc', 'matricula', 'anexo'));
    }

    public function putEdit($lancamentotccId, LancamentoTccRequest $request)
    {
        try {
            $lancamentotcc = $this->lancamentotccRepository->find($lancamentotccId);

            if (!$lancamentotcc) {
                flash()->error('Lançamento de TCC não existe.');
                return redirect()->route('academico.lancamentostccs.index');
            }

            $dados = $request->only($this->lancamentotccRepository->getFillableModelFields());
            $dados['ltc_anx_tcc'] = $lancamentotcc->ltc_anx_tcc;


            if ($request->file('ltc_file') != null) {
                // Novo Anexo
                $anexoTcc = $request->file('ltc_file');

                if ($lancamentotcc->ltc_anx_tcc != null) {
                    // Atualiza anexo
                    $atualizaAnexo = $this->anexoRepository->atualizarAnexo($lancamentotcc->ltc_anx_tcc, $anexoTcc);

                    if ($atualizaAnexo['type'] == 'error_non_existent') {
                        flash()->error($atualizaAnexo['message']);
                        return redirect()->back();
                    }

                    if ($atualizaAnexo['type'] == 'error_exists') {
                        flash()->error($atualizaAnexo['message']);
                        return redirect()->back()->withInput($request->all());
                    }

                    if (!$atualizaAnexo) {
                        flash()->error('ocorreu um problema ao salvar o arquivo');
                        return redirect()->back()->withInput($request->all());
                    }
                } else {
                    // Cria um novo anexo caso o documento nao tenha anteriormente
                    $anexo = $this->anexoRepository->salvarAnexo($anexoTcc);

                    if ($anexo['type'] == 'error_exists') {
                        flash()->error($anexo['message']);
                        return redirect()->back()->withInput($request->all());
                    }

                    if (!$anexo) {
                        flash()->error('ocorreu um problema ao salvar o arquivo');
                        return redirect()->back()->withInput($request->all());
                    }

                    $dados['ltc_anx_tcc'] = $anexo->anx_id;
                }
            }


            if (!$this->lancamentotccRepository->update($dados, $lancamentotcc->ltc_id, 'ltc_id')) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Lançamento de TCC atualizado com sucesso.');
            return redirect()->route('academico.lancamentostccs.alunosturma', $lancamentotcc->matriculaOferta->matriculaCurso->turma->trm_id);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }
}
