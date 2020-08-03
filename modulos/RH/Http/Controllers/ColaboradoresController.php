<?php

namespace Modulos\RH\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modulos\RH\Http\Requests\ColaboradorRequest;
use Modulos\RH\Repositories\ColaboradorRepository;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Geral\Http\Requests\PessoaRequest;
use Modulos\Geral\Repositories\DocumentoRepository;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use ActionButton;

class ColaboradoresController extends BaseController
{
    protected $colaboradorRepository;
    protected $pessoaRepository;
    protected $documentoRepository;

    public function __construct(ColaboradorRepository $colaborador, PessoaRepository $pessoa, DocumentoRepository $documento)
    {
        $this->colaboradorRepository = $colaborador;
        $this->pessoaRepository = $pessoa;
        $this->documentoRepository = $documento;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setRoute('rh.colaboradores.create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->colaboradorRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'col_id' => '#',
                'pes_nome' => 'Nome',
                'pes_email' => 'Email',
                'col_action' => 'Ações'
            ))
                ->modifyCell('col_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('col_action', 'col_id')
                ->modify('col_action', function ($id) {
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
                                'route' => 'rh.colaboradores.edit',
                                'parameters' => ['id' => $id],
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-eye',
                                'route' => 'rh.colaboradores.show',
                                'parameters' => ['id' => $id],
                                'label' => 'Visualizar',
                                'method' => 'get'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('col_id', 'pes_nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('RH::colaboradores.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate(Request $request)
    {
        $pessoaId = $request->get('id');

        $colaboradors = $this->colaboradorRepository->search(array(['col_pes_id', '=', $pessoaId]));

        if (!$colaboradors->isEmpty()) {
            flash()->error('Este CPF já tem um colaborador cadastrado!');
            return redirect()->route('rh.colaboradores.index');
        }

        if (!is_null($pessoaId)) {
            $pessoa = $this->pessoaRepository->findById($pessoaId);

            if ($pessoa) {
                return view('RH::colaboradores.create', ['pessoa' => $pessoa]);
            }
        }

        return view('RH::colaboradores.create', ['pessoa' => []]);
    }

    public function postCreate(Request $request)
    {

        $data = $request->all();

        $colaboradorRequest = new ColaboradorRequest();


        try {

            $cpf = $request->input('doc_conteudo');

            $dataForm = $request->all();
            $pes_id = isset($dataForm['pes_id']) ? $request->input('pes_id') : null;

            if ($this->documentoRepository->verifyCpf($request->input('doc_conteudo'), $pes_id)) {
                $errors = ['doc_conteudo' => 'CPF já cadastrado'];
                return redirect()->back()->with('validado', true)->withInput($request->all())->withErrors($errors);
            }

            if ($pes_id) {

                $data['col_pes_id'] = $pes_id;

                $validator = Validator::make($request->all(), $colaboradorRequest->rules($pes_id));
                if ($validator->fails()) {
                    return redirect()->back()->with('validado', true)->withInput($request->all())->withErrors($validator);
                }

                DB::beginTransaction();
                $this->pessoaRepository->update($data, $pes_id, 'pes_id');
            } else {
                $validator = Validator::make($data, $colaboradorRequest->rules());

                if ($validator->fails()) {
                    return redirect()->back()->with('validado', true)->withInput($request->all())->withErrors($validator);
                }

                DB::beginTransaction();
                $pessoa = $this->pessoaRepository->create($data);
                $data['col_pes_id'] = $pessoa->pes_id;
            }


            $colaborador = $this->colaboradorRepository->create($data);

            $dataDocumento = array(
                'doc_tpd_id' => 2,
                'doc_conteudo' => $cpf,
                'doc_pes_id' => $data['col_pes_id']
            );

            $this->documentoRepository->updateOrCreate(['doc_pes_id' => $data['col_pes_id'], 'doc_tpd_id' => 2], $dataDocumento);

            flash()->success('Colaborador criado com sucesso!');

            DB::commit();
            return redirect()->route('rh.colaboradores.show', $colaborador->col_id);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
            DB::rollback();
            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

            return redirect()->back()->with('validado', true);
        }
    }

    public function getEdit($colaboradorId)
    {
        $colaborador = $this->colaboradorRepository->find($colaboradorId);

        if (!$colaborador) {
            flash()->error('Colaborador não existe.');
            return redirect()->back();
        }

        $pessoa = $this->pessoaRepository->findById($colaborador->col_pes_id);

        return view('RH::colaboradores.edit', ['pessoa' => $pessoa, 'colaborador' => $colaborador]);
    }

    public function putEdit($colaboradorId, Request $request)
    {

        $data = $request->all();

        $colaboradorRequest = new ColaboradorRequest();

        $colaborador = $this->colaboradorRepository->find($colaboradorId);

        $pessoa = $colaborador->pessoa;

        $validator = Validator::make($data, $colaboradorRequest->rules($pessoa->pes_id));

        if ($validator->fails()) {
            return redirect()->back()->with('validado', true)->withInput($request->all())->withErrors($validator);
        }

        if (!$pessoa) {
            flash()->error('Pessoa não existe.');
            return redirect()->route('rh.colaboradores.index');
        }

        if ($this->documentoRepository->verifyCpf($request->input('doc_conteudo'), $pessoa->pes_id)) {
            $errors = ['doc_conteudo' => 'CPF já cadastrado'];
            return redirect()->back()->withInput($request->all())->withErrors($errors);
        }

        DB::beginTransaction();
        try {
            $oldPessoa = clone $pessoa;

            $pessoa->fill($request->all())->save();

            $this->colaboradorRepository->update($data,$colaboradorId);

            $dataDocumento = [
                'doc_pes_id' => $pessoa->pes_id,
                'doc_conteudo' => $request->input('doc_conteudo'),
                'doc_tpd_id' => 2
            ];

            $this->documentoRepository->updateOrCreate(['doc_pes_id' => $pessoa->pes_id, 'doc_tpd_id' => 2], $dataDocumento);

            DB::commit();

            if ($this->checkUpdateMigracao($oldPessoa, $pessoa)) {
                $this->pessoaRepository->updatePessoaAmbientes($pessoa);
            }

            flash()->success('Colaborador editado com sucesso!');
            return redirect()->route('rh.colaboradores.show', $pessoa->colaborador->col_id);
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->all())->withErrors($e);
        } catch (\Exception $e) {
            DB::rollback();
            if (config('app.debug')) {
                throw $e;
            }

            flash()->error('Erro ao tentar editar. Caso o problema persista, entre em contato com o suporte.');

            return redirect()->back();
        }
    }

    public function getShow($colaboradorId)
    {
        $colaborador = $this->colaboradorRepository->find($colaboradorId);
        session(['last_acad_route' => 'rh.colaboradores.show', 'last_id' => $colaboradorId]);

        if (!$colaborador) {
            flash()->error('Colaborador não existe.');
            return redirect()->back();
        }

        $situacao = [
            'cursando' => 'Cursando',
            'reprovado' => 'Reprovado',
            'evadido' => 'Evadido',
            'trancado' => 'Trancado',
            'desistente' => 'Desistente'
        ];

        return view('RH::colaboradores.show', ['pessoa' => $colaborador->pessoa, 'colaborador' => $colaborador, 'situacao' => $situacao]);
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
