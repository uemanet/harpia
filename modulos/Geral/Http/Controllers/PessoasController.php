<?php

namespace Modulos\Geral\Http\Controllers;

use Harpia\Format\Format;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Geral\Http\Requests\PessoaRequest;
use Modulos\Geral\Repositories\DocumentoRepository;
use Modulos\Geral\Repositories\PessoaRepository;
use Illuminate\Http\Request;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use DB;

class PessoasController extends BaseController
{
    protected $pessoaRepository;
    protected $documentoRepository;

    public function __construct(PessoaRepository $pessoa, DocumentoRepository $documento)
    {
        $this->pessoaRepository = $pessoa;
        $this->documentoRepository = $documento;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setAction('/geral/pessoas/create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $tabela = null;
        $paginacao = null;

        $tableData = $this->pessoaRepository->paginateRequest($request->all());
        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'pes_id' => '#',
                'pes_nome' => 'Nome',
                'pes_email' => 'Email',
                'doc_conteudo' => 'CPF',
                'pes_action' => 'Ações'
            ))
                ->modifyCell('pes_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('pes_action', 'pes_id')
                ->modify('pes_action', function ($id) {
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
                                'action' => '/geral/pessoas/edit/' . $id,
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-eye',
                                'action' => '/geral/pessoas/show/'.$id,
                                'label' => 'Visualizar',
                                'method' => 'get'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('pes_id', 'pes_nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('Geral::pessoas.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate()
    {
        return view('Geral::pessoas.create');
    }

    public function postCreate(PessoaRequest $request)
    {
        DB::beginTransaction();

        try {
            $dataPessoa = $request->except('doc_conteudo');

            $dataPessoa['pes_email'] = strtolower($dataPessoa['pes_email']);

            $pessoa = $this->pessoaRepository->create($dataPessoa);

            $dataDocumento = [
                'doc_pes_id' => $pessoa->pes_id,
                'doc_conteudo' => $request->input('doc_conteudo'),
                'doc_tpd_id' => 2
            ];

            $this->documentoRepository->create($dataDocumento);

            DB::commit();

            flash()->success('Pessoa criada com sucesso');
            return redirect()->route('geral.pessoas.index');
        } catch (\Exception $e) {
            DB::rollback();
            if (config('app.debug')) {
                throw $e;
            }

            flash()->success('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }

    public function getEdit($id)
    {
        $pessoa = $this->pessoaRepository->findById($id);

        return view('Geral::pessoas.edit', compact('pessoa'));
    }

    public function putEdit($id, PessoaRequest $request)
    {
        DB::beginTransaction();

        try {
            if ($this->pessoaRepository->verifyEmail($request->input('pes_email'), $id)) {
                $errors = ['pes_email' => 'Email já cadastrado'];
                return redirect()->back()->withInput($request->all())->withErrors($errors);
            }

            if ($this->documentoRepository->verifyCpf($request->input('doc_conteudo'), $id)) {
                $errors = ['doc_conteudo' => 'CPF já cadastrado'];
                return redirect()->back()->withInput($request->all())->withErrors($errors);
            }
            
            $dataPessoa = $request->except('doc_conteudo', '_method', '_token');

            $this->pessoaRepository->update($dataPessoa, $id, 'pes_id');

            $dataDocumento = [
                'doc_pes_id' => $id,
                'doc_conteudo' => $request->input('doc_conteudo'),
                'doc_tpd_id' => 2
            ];

            $this->documentoRepository->updateOrCreate(['doc_pes_id' => $id, 'doc_tpd_id' => 2], $dataDocumento);

            DB::commit();

            flash()->success('Pessoa editada com sucesso!');
            return redirect()->route('geral.pessoas.index');
        } catch (\Exception $e) {
            DB::rollback();
            if (config('app.debug')) {
                throw $e;
            }
            flash()->danger('Erro ao tentar editar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }
    }
    
    public function getShow($id)
    {
        $pessoa = $this->pessoaRepository->find($id);
        
        $pessoa->pes_sexo = ($pessoa->pes_sexo == 'M') ? 'Masculino' : 'Feminino';

        $pessoa->pes_nascimento = Format::formatDate($pessoa->pes_nascimento, 'd/m/Y');

        $pessoa->pes_necessidade_especial = ($pessoa->pes_necessidade_especial == 'S') ? 'Sim' : 'Não';

        $pessoa->pes_estrangeiro = ($pessoa->pes_estrangeiro) ? 'Sim' : 'Não';

        $documentos = [];

        foreach ($pessoa->documentos as $documento) {
            $obj = array();

            $obj['tipo'] = $documento->tipo_documento->tpd_nome;
            $obj['conteudo'] = $documento->doc_conteudo;

            if ($obj['tipo'] == 'CPF') {
                $obj['conteudo'] = Format::mask($obj['conteudo'], '###.###.###-##');
            }

            $obj['orgao'] = $documento->doc_orgao;
            $obj['emissao'] = isset($documento->doc_dataexpedicao) ? Format::formatDate($documento->doc_dataexpedicao, 'd/m/Y') : null;
            $obj['observacao'] = $documento->doc_observacao;

            $documentos[] = (object) $obj;
        }

        return view('Geral::pessoas.show', ['pessoa' => $pessoa, 'documentos' => $documentos]);
    }

    public function getVerificapessoa(Request $request)
    {
        $cpf = $request->input('doc_conteudo');
        $route = $request->input('rota');

        $cpf = str_replace(['.', '-'], '', $cpf);

        $pessoa = $this->pessoaRepository->findPessoaByCpf($cpf);

        if ($pessoa) {
            return redirect()->route($route, ['id' => $pessoa->pes_id])->with('validado', 'true');
        }

        return redirect()->route($route)->with('validado', 'true');
    }
}
