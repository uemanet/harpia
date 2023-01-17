<?php

namespace Modulos\Academico\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modulos\Academico\Http\Requests\NoticiaRequest;
use Modulos\Academico\Models\Noticia;
use Modulos\Academico\Repositories\NoticiaRepository;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;

class NoticiasController extends BaseController
{
    protected $noticiaRepository;

    public function __construct(NoticiaRepository $noticiaRepository)
    {
        $this->noticiaRepository = $noticiaRepository;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setRoute('academico.noticias.create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->noticiaRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'not_id' => '#',
                'not_titulo' => 'Título',
                'not_action' => 'Ações'
            ))
                ->modifyCell('not_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('not_action', 'not_id')
                ->modify('not_action', function ($id) {
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
                                'route' => 'academico.noticias.edit',
                                'parameters' => ['id' => $id],
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => 'btn-delete text-red',
                                'icon' => 'fa fa-trash',
                                'route' => 'academico.noticias.delete',
                                'id' => $id,
                                'label' => 'Excluir',
                                'method' => 'post'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('not_id', 'not_titulo'));

            $paginacao = $tableData->appends($request->except('page'));
        }
        return view('Academico::noticias.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCreate()
    {
        return view('Academico::noticias.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postCreate(Request $request)
    {
//        dd($request);
//        exit();

        try {
            $noticia = $this->noticiaRepository->create($request->all());

            if (!$noticia) {
                flash()->error('Erro ao tentar salvar.');

                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Notícia criada com sucesso.');
            return redirect()->route('academico.noticias.index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Modulos\Academico\Models\Noticia  $noticia
     * @return \Illuminate\Http\Response
     */
    public function show(Noticia $noticia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Modulos\Academico\Models\Noticia  $noticia
     * @return \Illuminate\Http\Response
     */
    public function getEdit($noticiaId)
    {
        $noticia = $this->noticiaRepository->find($noticiaId);

        if (!$noticia) {
            flash()->error('Notícia não existe.');
            return redirect()->back();
        }

        return view('Academico::noticias.edit', compact('noticia'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Modulos\Academico\Models\Noticia  $noticia
     * @return \Illuminate\Http\Response
     */
    public function putEdit($noticiaId, NoticiaRequest $request)
    {
        try {
            $noticia = $this->noticiaRepository->find($noticiaId);

            if (!$noticia) {
                flash()->error('Notícia não existe.');
                return redirect()->route('academico.noticias.index');
            }

            $requestData = $request->only($this->noticiaRepository->getFillableModelFields());

            if (!$this->noticiaRepository->update($requestData, $noticia->not_id, 'not_id')) {
                flash()->error('Erro ao tentar salvar.');
                return redirect()->back()->withInput($request->all());
            }

            flash()->success('Notícia atualizada com sucesso.');
            return redirect()->route('academico.noticias.index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar atualizar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Modulos\Academico\Models\Noticia  $noticia
     * @return \Illuminate\Http\Response
     */
    public function postDelete(Request $request)
    {
        try {
            $noticiaId = $request->get('id');

            $this->noticiaRepository->delete($noticiaId);

            flash()->success('Notícia excluída com sucesso.');

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
}
