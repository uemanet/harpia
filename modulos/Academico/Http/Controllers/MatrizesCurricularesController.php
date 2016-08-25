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
        try{
            DB::beginTransaction();

            $anexo = $request->file('mtc_file');
            $anexoCriado = $this->salvarAnexo($anexo);

            $dados = $request->all();
            unset($dados['mtc_file']);

            $dados['mtc_anx_projeto_pedagogico'] = $anexoCriado->anx_id;

            $matrizCurricular = $this->matrizCurricularRepository->create($dados);

            if(!$matrizCurricular){
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            DB::commit();

            flash()->success('Matriz Curricular criada com sucesso.');
            return redirect('/academico/matrizescurriculares');

        } catch (\Exception $e){
            if (config('app.debug')) {
                throw $e;
            }

            DB::rollBack();

            flash()->success('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param int $tipoAnexo
     * @return \Illuminate\Http\RedirectResponse|static
     * @throws FileExistsException
     * @throws \Exception
     */
    private function salvarAnexo(UploadedFile $uploadedFile, $tipoAnexo = 1)
    {
        $hash = sha1_file($uploadedFile);

        $pDir = substr($hash, 0, 2); // first Directory
        $sDir = substr($hash, 2, 2); // second Directory

        $caminhoArquivo = storage_path(). '/uploads' . DIRECTORY_SEPARATOR. $pDir .DIRECTORY_SEPARATOR. $sDir;

        if (file_exists($caminhoArquivo.$hash)){
            if(config('app.debug')){
                throw new FileExistsException();
            }
            flash()->error('Arquivo já existe !');
            return redirect()->back();
        }

        try{
            $anexo = [
                'anx_tax_id' => $tipoAnexo,
                'anx_nome' => $uploadedFile->getClientOriginalName(),
                'anx_mime' => $uploadedFile->getClientMimeType(),
                'anx_localizacao' => $caminhoArquivo
            ];

            $uploadedFile->move($caminhoArquivo, $hash);

            return $this->anexoRepository->create($anexo);

        } catch (\FileException $e){
            if(config('app.debug')){
                throw $e;
            }
            flash()->error('Ocorreu um problema ao salvar o arquivo!');
        } catch (\Exception $e){
            if(config('app.debug')){
                throw $e;
            }
            flash()->error('Ocorreu um problema ao salvar o arquivo!');
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

            if (!$matrizCurricular) {
                flash()->error('Matriz curricular não existe.');
                return redirect('/academico/matrizescurriculares');
            }

            if($request->file('mtc_file')){

            }

            $requestData = $request->only($this->matrizCurricularRepository->getFillableModelFields());

            if (!$this->matrizCurricularRepository->update($requestData, $matrizCurricular->mtc_id, 'mtc_id')) {

                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Matriz Curricular atualizada com sucesso.');

            return redirect('/academico/matrizescurriculares');
        } catch (\Exception $e) {
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
