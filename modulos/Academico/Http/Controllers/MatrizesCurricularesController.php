<?php

namespace Modulos\Academico\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use League\Flysystem\FileExistsException;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Geral\Http\Requests\AnexoRequest;
use Modulos\Geral\Repositories\AnexoRepository;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Illuminate\Http\Request;
use Modulos\Academico\Repositories\MatrizCurricularRepository;
use Modulos\Academico\Http\Requests\MatrizCurricularRequest;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MatrizesCurricularesController extends BaseController
{
    protected $matrizCurricularRepository;
    protected $cursoRepository;
    protected $anexoRepository;

    public function __construct(MatrizCurricularRepository $matrizCurricularRepository,
                                CursoRepository $cursoRepository,
                                AnexoRepository $anexoRepository)
    {
        $this->matrizCurricularRepository = $matrizCurricularRepository;
        $this->cursoRepository = $cursoRepository;
        $this->anexoRepository = $anexoRepository;
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
            ->means('mtc_crs_id', 'curso')
            ->modify('mtc_crs_id', function ($curso) {
                return $curso->crs_nome;
            })
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
            DB::beginTransaction();

            $projetoPegagogico = $request->file('mtc_file');
            $anexoCriado = $this->anexoRepository->salvarAnexo($projetoPegagogico);

            $dados = $request->all();
            unset($dados['mtc_file']);

            $dados['mtc_anx_projeto_pedagogico'] = $anexoCriado->anx_id;

            $matrizCurricular = $this->matrizCurricularRepository->create($dados);

            if (!$matrizCurricular) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            DB::commit();

            flash()->success('Matriz Curricular criada com sucesso.');
            return redirect('/academico/matrizescurriculares');
        } catch (\Exception $e) {
            DB::rollBack();

            if (config('app.debug')) {
                throw $e;
            }
            flash()->success('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
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

    public function putEdit($matrizCurricularId, MatrizCurricularRequest $request)
    {
        try {
            DB::beginTransaction();
            $matrizCurricular = $this->matrizCurricularRepository->find($matrizCurricularId);
            $dados = $request->only($this->matrizCurricularRepository->getFillableModelFields());

            if ($request->file('mtc_file') != null) {
                // Novo Anexo
                $projetoPedagogico = $request->file('mtc_file');

                // Atualiza anexo
                $this->anexoRepository->atualizarAnexo($matrizCurricular->mtc_anx_projeto_pedagogico, $projetoPedagogico);
            }

            $dados['mtc_anx_projeto_pedagogico'] = $matrizCurricular->mtc_anx_projeto_pedagogico;
            if (!$this->matrizCurricularRepository->update($dados, $matrizCurricular->mtc_id, 'mtc_id')) {
                DB::rollBack();
                flash()->error('Erro ao tentar atualizar');
                return redirect()->back()->withInput($request->all());
            }

            DB::commit();
            flash()->success('Matriz Curricular atualizada com sucesso.');
            return redirect('/academico/matrizescurriculares');
        } catch (\Exception $e) {
            DB::rollBack();
            if (config('app.debug')) {
                throw $e;
            }
            flash()->success('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }


    public function postDelete(Request $request)
    {
        try {
            $matrizCurricularId = $request->get('id');

            $matrizCurricular = $this->matrizCurricularRepository->find($matrizCurricularId);


            if ($this->matrizCurricularRepository->delete($matrizCurricularId)) {
                // Excluir Anexo correspondente
                $this->anexoRepository->deletarAnexo($matrizCurricular->mtc_anx_projeto_pedagogico);
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
