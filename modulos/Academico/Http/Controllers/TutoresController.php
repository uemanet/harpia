<?php

namespace Modulos\Academico\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modulos\Academico\Http\Requests\TutorRequest;
use Modulos\Academico\Repositories\TutorRepository;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Geral\Http\Requests\PessoaRequest;
use Modulos\Geral\Repositories\DocumentoRepository;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;

class TutoresController extends BaseController
{
    protected $tutorRepository;
    protected $pessoaRepository;
    protected $documentoRepository;

    public function __construct(TutorRepository $tutor, PessoaRepository $pessoa, DocumentoRepository $documento)
    {
        $this->tutorRepository = $tutor;
        $this->pessoaRepository = $pessoa;
        $this->documentoRepository = $documento;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setRoute('academico.tutores.create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->tutorRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'tut_id' => '#',
                'pes_nome' => 'Nome',
                'pes_email' => 'Email',
                'tut_action' => 'Ações'
            ))
                ->modifyCell('tut_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('tut_action', 'tut_id')
                ->modify('tut_action', function ($id) {
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
                                'route' => 'academico.tutores.edit',
                                'parameters' => ['id' => $id],
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-eye',
                                'route' => 'academico.tutores.show',
                                'parameters' => ['id' => $id],
                                'label' => 'Visualizar',
                                'method' => 'get'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('tut_id', 'pes_nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('Academico::tutores.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate(Request $request)
    {
        $pessoaId = $request->get('id');

        $tutor = $this->tutorRepository->search(array(['tut_pes_id', '=', $pessoaId]));

        if (!$tutor->isEmpty()) {
            flash()->error('Este CPF já tem um tutor cadastrado!');
            return redirect()->route('academico.tutores.index');
        }

        if (!is_null($pessoaId)) {
            $pessoa = $this->pessoaRepository->findById($pessoaId);

            if ($pessoa) {
                return view('Academico::tutores.create', ['pessoa' => $pessoa]);
            }
        }

        return view('Academico::tutores.create', ['pessoa' => []]);
    }

    public function postCreate(Request $request)
    {
        $pessoaRequest = new PessoaRequest();
        $tutorRequest = new TutorRequest();

        try {
            $dataPessoa = array(
                'pes_nome' => $request->input('pes_nome'),
                'pes_sexo' => $request->input('pes_sexo'),
                'pes_email' => $request->input('pes_email'),
                'pes_telefone' => $request->input('pes_telefone'),
                'pes_nascimento' => $request->input('pes_nascimento'),
                'pes_mae' => $request->input('pes_mae'),
                'pes_pai' => $request->input('pes_pai'),
                'pes_estado_civil' => $request->input('pes_estado_civil'),
                'pes_naturalidade' => $request->input('pes_naturalidade'),
                'pes_nacionalidade' => $request->input('pes_nacionalidade'),
                'pes_raca' => $request->input('pes_raca'),
                'pes_necessidade_especial' => $request->input('pes_necessidade_especial'),
                'pes_estrangeiro' => $request->input('pes_estrangeiro'),
                'pes_endereco' => $request->input('pes_endereco'),
                'pes_numero' => $request->input('pes_numero'),
                'pes_complemento' => $request->input('pes_complemento'),
                'pes_cep' => $request->input('pes_cep'),
                'pes_bairro' => $request->input('pes_bairro'),
                'pes_cidade' => $request->input('pes_cidade'),
                'pes_estado' => $request->input('pes_estado')
            );

            $cpf = $request->input('doc_conteudo');

            $dataForm = $request->all();
            $pes_id = isset($dataForm['pes_id']) ? $request->input('pes_id') : null;

            if ($this->documentoRepository->verifyCpf($request->input('doc_conteudo'), $pes_id)) {
                $errors = ['doc_conteudo' => 'CPF já cadastrado'];
                return redirect()->back()->with('validado', true)->withInput($request->all())->withErrors($errors);
            }

            if ($pes_id) {
                $dataPessoa['pes_id'] = $pes_id;

                $validator = Validator::make($dataPessoa, $pessoaRequest->rules($pes_id));


                if ($validator->fails()) {
                    return redirect()->back()->with('validado', true)->withInput($request->all())->withErrors($validator);
                }

                DB::beginTransaction();
                $this->pessoaRepository->update($dataPessoa, $pes_id, 'pes_id');
            } else {
                $validator = Validator::make($request->all(), $pessoaRequest->rules());

                if ($validator->fails()) {
                    return redirect()->back()->with('validado', true)->withInput($request->all())->withErrors($validator);
                }
                DB::beginTransaction();
                $pessoa = $this->pessoaRepository->create($dataPessoa);
            }

            $dataDocumento = array(
                'doc_tpd_id' => 2,
                'doc_conteudo' => $cpf,
                'doc_pes_id' => $pes_id
            );

            $this->documentoRepository->updateOrCreate(['doc_pes_id' => $pes_id, 'doc_tpd_id' => 2], $dataDocumento);

            $validator = Validator::make(['tut_pes_id' => $pes_id], $tutorRequest->rules());

            if ($validator->fails()) {
                flash()->error('Tutor já cadastrado!');
                return redirect()->route('academico.tutores.index');
            }

            $this->tutorRepository->create(['tut_pes_id' => $pes_id]);

            DB::commit();

            flash()->success('Tutor criado com sucesso!');

            return redirect()->route('academico.tutores.index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
            DB::rollback();
            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

            return redirect()->back()->with('validado', true);
        }
    }

    public function getEdit($tutorId)
    {
        $tutor = $this->tutorRepository->find($tutorId);

        if (!$tutor) {
            flash()->error('Tutor não existe.');
            return redirect()->back();
        }

        $pessoa = $this->pessoaRepository->findById($tutor->tut_pes_id);

        return view('Academico::tutores.edit', ['pessoa' => $pessoa]);
    }

    public function putEdit($pessoaId, Request $request)
    {
        $pessoaRequest = new PessoaRequest();

        $validation = Validator::make($request->all(), $pessoaRequest->rules($pessoaId));

        if ($validation->fails()) {
            return redirect()->back()->withInput($request->all())->withErrors($validation->messages());
        }

        $pessoa = $this->pessoaRepository->find($pessoaId);

        if (!$pessoa) {
            flash()->error('Pessoa não existe.');
            return redirect()->route('academico.professores.index');
        }

        if ($this->documentoRepository->verifyCpf($request->input('doc_conteudo'), $pessoaId)) {
            $errors = ['doc_conteudo' => 'CPF já cadastrado'];
            return redirect()->back()->withInput($request->all())->withErrors($errors);
        }

        DB::beginTransaction();
        try {
            $oldPessoa = clone $pessoa;

            $pessoa->fill($request->all())->save();

            $dataDocumento = [
                'doc_pes_id' => $pessoaId,
                'doc_conteudo' => $request->input('doc_conteudo'),
                'doc_tpd_id' => 2
            ];

            $this->documentoRepository->updateOrCreate(['doc_pes_id' => $pessoaId, 'doc_tpd_id' => 2], $dataDocumento);

            DB::commit();

            if ($this->checkUpdateMigracao($oldPessoa, $pessoa)) {
                $this->pessoaRepository->updatePessoaAmbientes($pessoa);
            }

            flash()->success('Tutor editado com sucesso!');
            return redirect()->route('academico.tutores.index');
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())->withErrors($e);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
            DB::rollback();
            flash()->error('Erro ao tentar editar. Caso o problema persista, entre em contato com o suporte.');

            return redirect()->back();
        }
    }

    public function getShow($tutorId)
    {
        $tutor = $this->tutorRepository->find($tutorId);

        if (!$tutor) {
            flash()->error('Tutor não existe.');
            return redirect()->back();
        }

        session(['last_acad_route' => 'academico.tutores.show', 'last_id' => $tutorId]);

        return view('Academico::tutores.show', ['pessoa' => $tutor->pessoa]);
    }

    private function checkUpdateMigracao($oldPessoa, $pessoa)
    {
        if (strcmp($oldPessoa->pes_nome, $pessoa->pes_nome) != 0 || strcmp($oldPessoa->pes_email, $pessoa->pes_email) != 0
            || strcmp($oldPessoa->pes_cidade, $pessoa->pes_cidade) != 0) {
            return true;
        }

        return false;
    }
}
