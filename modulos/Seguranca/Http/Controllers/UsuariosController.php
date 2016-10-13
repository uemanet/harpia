<?php

namespace Modulos\Seguranca\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Validator;
use Modulos\Geral\Http\Requests\PessoaRequest;
use Modulos\Geral\Repositories\DocumentoRepository;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Geral\Repositories\TipoDocumentoRepository;
use Modulos\Seguranca\Http\Requests\UsuarioRequest;
use Modulos\Seguranca\Providers\ActionButton\Facades\ActionButton;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Seguranca\Repositories\UsuarioRepository;
use Illuminate\Http\Request;

class UsuariosController extends BaseController
{
    protected $usuarioRepository;
    protected $pessoaRepository;
    protected $documentoRepository;
    protected $tipoDocumentoRepository;

    public function __construct(
        UsuarioRepository $usuarioRepository, 
        PessoaRepository $pessoaRepository, 
        DocumentoRepository $documentoRepository,
        TipoDocumentoRepository $tipoDocumentoRepository
    )
    {
        $this->usuarioRepository = $usuarioRepository;
        $this->pessoaRepository = $pessoaRepository;
        $this->documentoRepository = $documentoRepository;
        $this->tipoDocumentoRepository = $tipoDocumentoRepository;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setAction('/seguranca/usuarios/create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->usuarioRepository->paginateRequest($request->all());
        
        if($tableData->count()) {
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
                            'action' => '/seguranca/usuarios/edit/' . $id,
                            'label' => 'Editar',
                            'method' => 'get'
                        ],
                        [
                            'classButton' => 'btn-delete text-red',
                            'icon' => 'fa fa-trash',
                            'action' => '/seguranca/usuarios/delete',
                            'id' => $id,
                            'label' => 'Excluir',
                            'method' => 'post'
                        ]
                    ]
                ]);
            })
            ->sortable(array('pes_id', 'pes_nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }
        

        return view('Seguranca::usuarios.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate($pesId = null)
    {
        if(is_null($pesId)){
            return view('Seguranca::usuarios.create', ['pessoa' => []]);
        }

        $pessoa = $this->pessoaRepository->findByIdForForm($pesId);

        if(!$pessoa->isEmpty()){
            $pessoa = $pessoa->first();
            return view('Seguranca::usuarios.create',['pessoa' => $pessoa]);
        }

        return view('Seguranca::usuarios.create', ['pessoa' => []]);
    }

    public function postCreate(Request $request)
    {
        $usuarioRequest = new UsuarioRequest();
        $pessoaRequest = new PessoaRequest();

        DB::beginTransaction();

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
                'pes_cpf' => $request->input('pes_cpf')
            );

            $validatorPessoa = Validator::make($dataPessoa, $pessoaRequest->rules());
            if($validatorPessoa->fails())
            {
                return redirect()->back()->with('validado', true)->withInput($request->except('usr_senha'))->withErrors($validatorPessoa);
            }

            $dataForm = $request->all();
            $pes_id = isset($dataForm['pes_id']) ? $request->input('pes_id') : null;

            if($pes_id)
            {

            } else{
                $cpf = $dataPessoa['pes_cpf'];
                unset($dataPessoa['pes_cpf']);

                $pessoa = $this->pessoaRepository->create($dataPessoa);
                $pes_id = $pessoa->pes_id;

                $tipoId = $this->tipoDocumentoRepository->search([['tpd_nome', '=', 'CPF']], ['tpd_id'])->first()->tpd_id;

                $dataDocumento = array(
                    'doc_pes_id' => $pes_id,
                    'doc_tpd_id' => $tipoId,
                    'doc_conteudo' => $cpf
                );

                $this->documentoRepository->create($dataDocumento);
            }
            
            $dataUsuario = array(
                'usr_usuario' => $request->input('usr_usuario'),
                'usr_senha' => $request->input('usr_senha'),
                'usr_ativo' => $request->input('usr_ativo')
            );

            $validatorUsuario = Validator::make($dataUsuario, $usuarioRequest->rules());

            if($validatorUsuario->fails())
            {
                DB::rollback();
                return redirect()->back()->with('validado', true)->withInput($request->except('usr_senha'))->withErrors($validatorUsuario);
            }

            $dataUsuario['usr_senha'] = bcrypt($dataUsuario['usr_senha']);
            $dataUsuario['usr_pes_id'] = $pes_id;

            $this->usuarioRepository->create($dataUsuario);

            DB::commit();

            flash()->success('Usuário criado com sucesso!');

            return redirect('/seguranca/usuarios/index');

        } catch(\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
            DB::rollback();
            flash()->danger('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

            return redirect()->back()->with('validado', true);
        }
    }
}
