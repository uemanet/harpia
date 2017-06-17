<?php

namespace Modulos\Academico\Http\Controllers;

use Modulos\Academico\Models\ConfiguracaoCurso;
use Modulos\Academico\Repositories\VinculoRepository;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Academico\Http\Requests\CursoRequest;
use Illuminate\Http\Request;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\DepartamentoRepository;
use Modulos\Academico\Repositories\NivelCursoRepository;
use Modulos\Academico\Repositories\ProfessorRepository;
use Auth;
use DB;

class CursosController extends BaseController
{
    protected $cursoRepository;
    protected $departamentoRepository;
    protected $nivelcursoRepository;
    protected $professorRepository;
    protected $vinculoRepository;

    public function __construct(CursoRepository $curso,
                                DepartamentoRepository $departamento,
                                NivelCursoRepository $nivelcurso,
                                ProfessorRepository $professor,
                                VinculoRepository $vinculoRepository)
    {
        $this->cursoRepository = $curso;
        $this->departamentoRepository = $departamento;
        $this->nivelcursoRepository = $nivelcurso;
        $this->professorRepository = $professor;
        $this->vinculoRepository = $vinculoRepository;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setRoute('academico.cursos.create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->cursoRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'crs_id' => '#',
                'crs_nome' => 'Curso',
                'crs_sigla' => 'Sigla',
                'crs_prf_diretor' => 'Diretor',
                'crs_descricao' => 'Descrição',
                'crs_action' => 'Ações',
            ))
                ->modifyCell('crs_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('crs_prf_diretor', 'diretor')
                ->modify('crs_prf_diretor', function ($diretor) {
                    return $diretor->pessoa->pes_nome;
                })
                ->means('crs_action', 'crs_id')
                ->modify('crs_action', function ($id) {
                    return ActionButton::grid([
                        'type' => 'SELECT',
                        'config' => [
                            'classButton' => 'btn-default',
                            'label' => 'Selecione'
                        ],
                        'buttons' => [
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-table',
                                'route' => 'academico.cursos.matrizescurriculares.index',
                                'parameters' => ['id' => $id],
                                'label' => 'Matrizes',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-pencil',
                                'route' => 'academico.cursos.edit',
                                'parameters' => ['id' => $id],
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'btn-delete text-red',
                                'icon' => 'fa fa-trash',
                                'route' => 'academico.cursos.delete',
                                'id' => $id,
                                'label' => 'Excluir',
                                'method' => 'post'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('crs_id', 'crs_nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }
        return view('Academico::cursos.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        $departamentos = $this->departamentoRepository->lists('dep_id', 'dep_nome');
        $niveiscursos = $this->nivelcursoRepository->lists('nvc_id', 'nvc_nome');
        $professores = $this->professorRepository->lists('prf_id', 'pes_nome');

        return view('Academico::cursos.create', ['departamentos' => $departamentos, 'niveiscursos' => $niveiscursos, 'professores' => $professores]);
    }

    public function postCreate(CursoRequest $request)
    {
        try {
            DB::beginTransaction();

            $dataCurso = $request->only(
                'crs_dep_id',
                'crs_nvc_id',
                'crs_prf_diretor',
                'crs_nome',
                'crs_sigla',
                'crs_descricao',
                'crs_resolucao',
                'crs_autorizacao',
                'crs_data_autorizacao',
                'crs_eixo',
                'crs_habilitacao'
            );

            // Salvar curso
            $curso = $this->cursoRepository->create($dataCurso);

            if (!$curso) {
                DB::rollback();
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            // Salvar configuracoes do curso
            $dataConfiguracoes = $request->only(
                'media_min_aprovacao',
                'media_min_final',
                'media_min_aprovacao_final',
                'modo_recuperacao',
                'conceitos_aprovacao'
            );

            foreach ($dataConfiguracoes as $nome => $valor) {
                if ($nome == 'conceitos_aprovacao') {
                    $valor = json_encode($valor);
                }

                ConfiguracaoCurso::create([
                    'cfc_crs_id' => $curso->crs_id,
                    'cfc_nome' => $nome,
                    'cfc_valor' => $valor
                ]);
            }

            // Cria vinculo com o curso para o usuario que esta criando o curso
            $vinculo = [
                'ucr_usr_id' => Auth::user()->usr_id,
                'ucr_crs_id' => $curso->crs_id,
            ];

            $this->vinculoRepository->create($vinculo);

            DB::commit();
            flash()->success('Curso criado com sucesso.');
            return redirect()->route('academico.cursos.index');
        } catch (\Exception $e) {
            DB::rollback();
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getEdit($cursoId)
    {
        $curso = $this->cursoRepository->find($cursoId);

        if (!$curso) {
            flash()->error('Curso não existe.');
            return redirect()->back();
        }

        $configuracoes = $curso->configuracoes;
        if ($configuracoes) {
            foreach ($configuracoes as $configuracao) {
                $valor = $configuracao->cfc_valor;
                if ($configuracao->cfc_nome == 'conceitos_aprovacao') {
                    $valor = json_decode($configuracao->cfc_valor, true);
                }

                $curso->{$configuracao->cfc_nome} = $valor;
            }
        }

        $departamentos = $this->departamentoRepository->lists('dep_id', 'dep_nome');
        $niveiscursos = $this->nivelcursoRepository->lists('nvc_id', 'nvc_nome');
        $professores = $this->professorRepository->listsEditCurso('prf_id', 'pes_nome', $cursoId);

        return view('Academico::cursos.edit', compact('curso', 'departamentos', 'niveiscursos', 'professores'));
    }

    public function putEdit($id, CursoRequest $request)
    {
        try {
            DB::beginTransaction();
            $curso = $this->cursoRepository->find($id);

            if (!$curso) {
                flash()->error('Curso não existe.');
                return redirect()->route('academico.cursos.index');
            }

            $requestData = $request->only($this->cursoRepository->getFillableModelFields());

            if (!$this->cursoRepository->update($requestData, $curso->crs_id, 'crs_id')) {
                DB::rollback();
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            $dataConfiguracoes = $request->only(
                'media_min_aprovacao',
                'media_min_final',
                'media_min_aprovacao_final',
                'modo_recuperacao',
                'conceitos_aprovacao'
            );

            foreach ($dataConfiguracoes as $nome => $valor) {
                if ($nome == 'conceitos_aprovacao') {
                    $valor = json_encode($valor);
                }

                $configuracao = ConfiguracaoCurso::where([
                    ['cfc_crs_id', '=', $curso->crs_id],
                    ['cfc_nome', '=', $nome]
                ])->first();

                $configuracao->cfc_valor = $valor;
                $configuracao->save();
            }

            DB::commit();
            flash()->success('Curso atualizado com sucesso.');
            return redirect()->route('academico.cursos.index');
        } catch (\Exception $e) {
            DB::rollback();
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar atualizar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function postDelete(Request $request)
    {
        $id = $request->get('id');

        $response = $this->cursoRepository->delete($id);

        flash()->{$response['status']}($response['message']);

        return redirect()->back();
    }
}
