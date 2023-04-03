<?php

namespace Modulos\Academico\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modulos\Academico\Http\Requests\InstituicaoRequest;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\InstituicaoRepository;
use Modulos\Academico\Repositories\MatriculaCursoRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;

class InstituicoesController extends BaseController
{
    protected $instituicaoRepository;
    protected $cursoRepository;
    protected $turmaRepository;
    protected $pessoaRepository;
    protected $matriculaCursoRepository;


    public function __construct(InstituicaoRepository $instituicaoRepository,
                                CursoRepository  $cursoRepository,
                                TurmaRepository  $turmaRepository,
                                PessoaRepository  $pessoaRepository,
                                MatriculaCursoRepository $matriculaCursoRepository)
    {
        $this->instituicaoRepository = $instituicaoRepository;
        $this->turmaRepository = $turmaRepository;
        $this->cursoRepository = $cursoRepository;
        $this->pessoaRepository = $pessoaRepository;
        $this->matriculaCursoRepository = $matriculaCursoRepository;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setRoute('academico.instituicoes.create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->instituicaoRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'itt_id' => '#',
                'itt_nome' => 'Instituição',
                'itt_sigla' => 'Sigla',
                'itt_action' => 'Ações'
            ))
                ->modifyCell('itt_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('itt_action', 'itt_id')
                ->modify('itt_action', function ($id) {
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
                                'route' => 'academico.instituicoes.edit',
                                'parameters' => ['id' => $id],
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-book',
                                'route' => 'academico.instituicoes.turmas',
                                'parameters' => ['id' => $id],
                                'label' => 'Turmas',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-user',
                                'route' => 'academico.instituicoes.pessoas',
                                'parameters' => ['id' => $id],
                                'label' => 'Pessoas',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'btn-delete text-red',
                                'icon' => 'fa fa-trash',
                                'route' => 'academico.instituicoes.delete',
                                'id' => $id,
                                'label' => 'Excluir',
                                'method' => 'post'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('itt_id', 'itt_nome', 'itt_sigla'));

            $paginacao = $tableData->appends($request->except('page'));
        }
        return view('Academico::instituicoes.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        return view('Academico::instituicoes.create');
    }

    public function postCreate(InstituicaoRequest $request)
    {
        try {
            $instituicao = $this->instituicaoRepository->create($request->all());

            if (!$instituicao) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Instituição criada com sucesso.');
            return redirect()->route('academico.instituicoes.index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getEdit($instituicaoId)
    {
        $instituicao = $this->instituicaoRepository->find($instituicaoId);

        if (!$instituicao) {
            flash()->error('Instituição não existe.');
            return redirect()->back();
        }

        return view('Academico::instituicoes.edit', compact('instituicao'));
    }
    public function putEdit($instituicaoId, InstituicaoRequest $request)
    {

        try {
            $instituicao = $this->instituicaoRepository->find($instituicaoId);

            if (!$instituicao) {
                flash()->error('Instituição não existe.');
                return redirect()->route('academico.instituicoes.index');
            }

            $requestData = $request->only($this->instituicaoRepository->getFillableModelFields());

            if (!$this->instituicaoRepository->update($requestData, $instituicao->itt_id, 'itt_id')) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Instituição atualizada com sucesso.');
            return redirect()->route('academico.instituicoes.index');
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
            $instituicaoId = $request->get('id');

            $this->instituicaoRepository->delete($instituicaoId);

            flash()->success('Instituição excluída com sucesso.');

            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            flash()->error('Erro ao tentar deletar');
            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar excluir. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getTurmas($instituicaoId ,Request $request)
    {
        try {
            $instituicao = $this->instituicaoRepository->find($instituicaoId);

            $cursos = $this->cursoRepository->all()->pluck('crs_nome','crs_id' )->toArray();;

            return view('Academico::instituicoes.turmas', compact('instituicao', 'cursos'));

            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            flash()->error('Erro');
            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar excluir. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function postTurmas($instituicaoId ,Request $request)
    {
        try {
            $data = $request->all();

            $this->turmaRepository->update([ 'trm_itt_id' => $instituicaoId ], $data['trm_id']);

            flash()->success('Turma vinculada com sucesso');

            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            flash()->error('Erro');
            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar excluir. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getPessoas($instituicaoId ,Request $request)
    {
        try {
            $instituicao = $this->instituicaoRepository->find($instituicaoId);
            $pessoasTodas = $this->pessoaRepository->all();
            $pessoasFiltradas = $pessoasTodas->filter(function ($pessoa) {
                return $pessoa->pes_itt_id === null;
            });

            $pessoas = $pessoasFiltradas->pluck('pes_nome','pes_id' )->toArray();

            return view('Academico::instituicoes.pessoas', compact('instituicao', 'pessoas'));

            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            flash()->error('Erro');
            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar excluir. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }


    public function postPessoas($instituicaoId ,Request $request)
    {
        try {
            $data = $request->all();

            $this->pessoaRepository->update([ 'pes_itt_id' => $instituicaoId ], $data['pes_id']);

            flash()->success('Pessoa vinculada com sucesso');

            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            flash()->error('Erro');
            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar excluir. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function postDeleteVinculo(Request $request)
    {
        try {
            $data = $request->all();
            $pes_id = $data['id'];

            //Verifica se a pessoa possui matrícula
            if($this->matriculaCursoRepository->verifyIfExistsMatricula($pes_id)){
                flash()->error('Esta pessoa possui matrícula ativa!');
                return redirect()->back();
            }

            $this->pessoaRepository->update([ 'pes_itt_id' => null ], $pes_id);

            flash()->success('Pessoa desvinculada com sucesso');
            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            flash()->error('Erro');
            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar excluir o vínculo. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }
}
