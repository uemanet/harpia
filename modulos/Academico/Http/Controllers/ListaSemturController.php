<?php

namespace Modulos\Academico\Http\Controllers;

use Harpia\Format\Format;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modulos\Academico\Http\Requests\ListaSemturRequest;
use Modulos\Academico\Repositories\CursoRepository;
use Modulos\Academico\Repositories\ListaSemturRepository;
use Modulos\Academico\Repositories\TurmaRepository;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;

class ListaSemturController extends BaseController
{
    protected $listaSemturRepository;
    protected $cursoRepository;
    protected $turmaRepository;

    public function __construct(ListaSemturRepository $listaSemturRepository, CursoRepository $cursoRepository, TurmaRepository $turmaRepository)
    {
        $this->listaSemturRepository = $listaSemturRepository;
        $this->cursoRepository = $cursoRepository;
        $this->turmaRepository = $turmaRepository;
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
                            'icon' => 'fa fa-eye',
                            'route' => 'academico.carteirasestudantis.showmatriculas',
                            'parameters' => ['id' => $obj->lst_id],
                            'label' => 'Gerenciar Matrículas',
                            'method' => 'get'
                        ],
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

    public function getShowMatriculas($id)
    {
        $lista = $this->listaSemturRepository->find($id);

        if (!$lista) {
            flash()->error('Lista de Carteiras de Estudantes não encontrada.');
            return redirect()->back();
        }

        $btnNovo = new TButton();
        $btnNovo->setName('Adicionar Matrículas')->setRoute('academico.carteirasestudantis.addmatriculas')->setParameters(['id' => $lista->lst_id])->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButton[] = $btnNovo;

        $turmas = $this->listaSemturRepository->getTurmasByLista($lista->lst_id);

        return view('Academico::carteirasestudantis.show', compact('lista', 'actionButton', 'turmas'));
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

    public function exportFile($listaId, $turmaId)
    {
        $lista = $this->listaSemturRepository->find($listaId);

        if (!$lista) {
            flash()->error('Lista de Carteiras de Estudantes não encontrada.');
            return redirect()->back();
        }

        $turma = $this->turmaRepository->find($turmaId);

        if (!$turma) {
            flash()->error('Turma não encontrada.');
            return redirect()->back();
        }

        $matriculas = $lista->matriculas()->where('mat_trm_id', $turmaId)->get();

        $nivel =
        [
          1 => 'GRD',
          2 => 'TEC',
          3 => 'TCG',
          4 => 'ESP',
          5 => 'MSC',
          6 => 'DTR',
          7 => 'APR'
        ];

        if ($matriculas->count()) {
            $filename = $turma->ofertacurso->curso->crs_nome . ' - '. $turma->trm_nome.'.txt';

            $content = '';

            foreach ($matriculas as $matricula) {
                if (!$matricula || $matricula->mat_situacao != 'cursando' || !$this->listaSemturRepository->validateMatricula($matricula)) {
                    continue;
                }

                $line = '7201';
                $line .= substr(str_pad(utf8_decode($matricula->aluno->pessoa->pes_nome), 50), 0, 50);
                $line .= substr(str_pad(utf8_decode($matricula->aluno->pessoa->pes_mae), 50), 0, 50);
                $line .= substr(str_pad(utf8_decode($matricula->aluno->pessoa->pes_pai), 50), 0, 50);
                $line .= $matricula->aluno->pessoa->pes_sexo;
                $line .= substr(str_pad(utf8_decode('EAD'.$nivel[$turma->ofertacurso->curso->crs_nvc_id].$turma->ofertacurso->curso->crs_id), 25), 0, 25);
                $line .= '2'; // grau
                $line .= '1'; // serie
                $line .= 'I'; // turno
                $line .= substr(str_pad(utf8_decode($turma->trm_nome), 5), 0, 5);
                $line .= substr(str_pad($matricula->mat_id, 12), 0, 12);
                $line .= substr($matricula->aluno->pessoa->pes_nascimento, 0, 2) . substr($matricula->aluno->pessoa->pes_nascimento, 3, 2) . substr($matricula->aluno->pessoa->pes_nascimento, 6, 4);
                $line .= substr(str_pad(utf8_decode($matricula->aluno->pessoa->pes_endereco), 50), 0, 50);
                $line .= substr(str_pad(utf8_decode($matricula->aluno->pessoa->pes_bairro), 30), 0, 30);
                $line .= substr(str_pad(utf8_decode($matricula->aluno->pessoa->pes_cidade), 20), 0, 20);
                $line .= substr(str_pad($matricula->aluno->pessoa->pes_cep, 8), 0, 8);
                $line .= substr(str_pad($matricula->aluno->pessoa->pes_celular, 10), 0, 10);

                $rg = $matricula->aluno->pessoa->documentos()->where('doc_tpd_id', 1)->first();

                $line .= substr(str_pad($rg->doc_conteudo, 20), 0, 20);
                $line .= substr(str_pad($rg->doc_orgao, 10), 0, 10);
                $line .= substr($rg->doc_data_expedicao, 0, 2) . substr($rg->doc_data_expedicao, 3, 2) . substr($rg->doc_data_expedicao, 6, 4);

                $cpf = $matricula->aluno->pessoa->documentos()->where('doc_tpd_id', 2)->first();
                $line .= $cpf->doc_conteudo;

                $content .= $line . PHP_EOL;
            }

            header("Content-Description: File Transfer");
            header("Content-Type: application/octet-stream");
            header("Content-disposition: attachment; filename={$filename}");
            header("Content-Length: ".strlen($content));
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Expires: 0");
            header("Pragma: public");

            echo $content;
            exit;
        }

        flash()->error('Não há matrículas nessa lista');
        return redirect()->back();
    }

    public function getPrint($listaId, $turmaId)
    {
        $lista = $this->listaSemturRepository->find($listaId);

        if (!$lista) {
            flash()->error('Lista de Carteiras de Estudantes não encontrada.');
            return redirect()->back();
        }

        $turma = $this->turmaRepository->find($turmaId);

        if (!$turma) {
            flash()->error('Turma não encontrada.');
            return redirect()->back();
        }

        $matriculas = $lista->matriculas()->where('mat_trm_id', $turmaId)->get();

        if (!$matriculas->count()) {
            flash()->error('Não há matrículas dessa turma incluídas nesta lista.');
            return redirect()->route('academico.carteirasestudantis.showmatriculas', $listaId);
        }

        foreach ($matriculas as $key => $value) {
            if (!$this->listaSemturRepository->validateMatricula($value)) {
                unset($matriculas[$key]);
            }
        }

        $mpdf = new \mPDF();
        $mpdf->mirrorMargins = 1;

        $title = $turma->ofertacurso->curso->crs_nome . ' - '. $turma->trm_nome;
        $mpdf->SetTitle($title);
        $mpdf->addPage('L');

        $mpdf->WriteHTML(view('Academico::carteirasestudantis.print', compact('matriculas', 'lista'))->render());
        $mpdf->Output($title.'.pdf', 'I');
    }

    public function postDeleteMatricula(Request $request)
    {
        $lista = $this->listaSemturRepository->find($request->get('lst_id'));

        if (!$lista) {
            return new JsonResponse('Lista de Carteiras de Estudantes não encontrada.', 400, [], JSON_UNESCAPED_UNICODE);
        }

        try {
            $lista->matriculas()->detach($request->get('mat_id'));

            return new JsonResponse('Matrícula excluída da lista.', 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return new JsonResponse('Exception: '.$e->getMessage(), 400, [], JSON_UNESCAPED_UNICODE);
            }

            return new JsonResponse('Erro ao tentar deletar. Caso o problema persista, entre em contato com o suporte.', 400, [], JSON_UNESCAPED_UNICODE);
        }
    }
}
