<?php

namespace Modulos\Seguranca\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modulos\Geral\Repositories\DocumentoRepository;
use Modulos\Seguranca\Repositories\PerfilRepository;
use Validator;
use Modulos\Geral\Http\Requests\PessoaRequest;
use Modulos\Geral\Repositories\PessoaRepository;
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
    protected $perfilRepository;

    public function __construct(
        UsuarioRepository $usuarioRepository,
        PessoaRepository $pessoaRepository,
        DocumentoRepository $documentoRepository,
        PerfilRepository $perfilRepository
    ) {
        $this->usuarioRepository = $usuarioRepository;
        $this->pessoaRepository = $pessoaRepository;
        $this->documentoRepository = $documentoRepository;
        $this->perfilRepository = $perfilRepository;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setRoute('seguranca.usuarios.create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->usuarioRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'usr_id' => '#',
                'pes_nome' => 'Nome',
                'pes_email' => 'Email',
                'usr_usuario' => 'Usuário',
                'pes_action' => 'Ações'
            ))
            ->modifyCell('pes_action', function () {
                return array('style' => 'width: 140px;');
            })
            ->means('pes_action', 'usr_id')
            ->modify('pes_action', function ($id) {
                return ActionButton::grid([
                    'type' => 'SELECT',
                    'config' => [
                        'classButton' => 'btn-default',
                        'label' => 'Selecione'
                    ],
                    'buttons' => [
                        [
                            'classButton' => 'text-blue',
                            'icon' => 'fa fa-check-square-o',
                            'route' => 'seguranca.usuarios.atribuirperfil',
                            'parameters' => ['id' => $id],
                            'label' => 'Atribuir Perfil',
                            'method' => 'get'
                        ],
                        [
                            'classButton' => '',
                            'icon' => 'fa fa-pencil',
                            'route' => 'seguranca.usuarios.edit',
                            'parameters' => ['id' => $id],
                            'label' => 'Editar',
                            'method' => 'get'
                        ],
                        [
                            'classButton' => 'btn-delete text-red',
                            'icon' => 'fa fa-trash',
                            'route' => 'seguranca.usuarios.delete',
                            'id' => $id,
                            'label' => 'Excluir',
                            'method' => 'post'
                        ]
                    ]
                ]);
            })
            ->sortable(array('usr_id', 'pes_nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('Seguranca::usuarios.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate($pesId = null)
    {
        if (!is_null($pesId)) {
            $pessoa = $this->pessoaRepository->findById($pesId);

            if ($pessoa) {
                return view('Seguranca::usuarios.create', ['pessoa' => $pessoa]);
            }
        }

        return view('Seguranca::usuarios.create', ['pessoa' => []]);
    }

    public function postCreate(Request $request)
    {
        $usuarioRequest = new UsuarioRequest();
        $pessoaRequest = new PessoaRequest();

        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), array_merge($usuarioRequest->rules(), $pessoaRequest->rules()));

            if ($validator->fails()) {
                return redirect()->back()->with('validado', true)->withInput($request->except('usr_senha'))->withErrors($validator);
            }

            $dataPessoa = array(
                'pes_nome' => $request->input('pes_nome'),
                'pes_sexo' => $request->input('pes_sexo'),
                'pes_email' => strtolower($request->input('pes_email')),
                'pes_telefone' => $request->input('pes_telefone'),
                'pes_nascimento' => $request->input('pes_nascimento'),
                'pes_mae' => $request->input('pes_mae'),
                'pes_pai' => $request->input('pes_pai'),
                'pes_estado_civil' => $request->input('pes_estado_civil'),
                'pes_naturalidade' => $request->input('pes_naturalidade'),
                'pes_nacionalidade' => $request->input('pes_nacionalidade'),
                'pes_raca' => $request->input('pes_raca'),
                'pes_necessidade_especial' => $request->input('pes_necessidade_especial'),
                'pes_estrangeiro' => $request->input('pes_estrangeiro')
            );

            $cpf = $request->input('doc_conteudo');

            $dataForm = $request->all();
            $pes_id = isset($dataForm['pes_id']) ? $request->input('pes_id') : null;

            if ($pes_id) {
                if ($this->pessoaRepository->verifyEmail($request->input('pes_email'), $pes_id)) {
                    $errors = ['pes_email' => 'Email já cadastrado'];
                    return redirect()->back()->with('validado', true)->withInput($request->except('usr_senha'))->withErrors($errors);
                }

                if ($this->documentoRepository->verifyCpf($request->input('doc_conteudo'), $pes_id)) {
                    $errors = ['doc_conteudo' => 'CPF já cadastrado'];
                    return redirect()->back()->with('validado', true)->withInput($request->except('usr_senha'))->withErrors($errors);
                }

                $this->pessoaRepository->update($dataPessoa, $pes_id, 'pes_id');

                $dataDocumento = array(
                    'doc_tpd_id' => 2,
                    'doc_conteudo' => $cpf,
                    'doc_pes_id' => $pes_id
                );

                $this->documentoRepository->updateOrCreate(['doc_pes_id' => $pes_id, 'doc_tpd_id' => 2], $dataDocumento);
            } else {
                if ($this->pessoaRepository->verifyEmail($request->input('pes_email'))) {
                    $errors = ['pes_email' => 'Email já cadastrado'];
                    return redirect()->back()->with('validado', true)->withInput($request->except('usr_senha'))->withErrors($errors);
                }

                if ($this->documentoRepository->verifyCpf($request->input('doc_conteudo'))) {
                    $errors = ['doc_conteudo' => 'CPF já cadastrado'];
                    return redirect()->back()->with('validado', true)->withInput($request->except('usr_senha'))->withErrors($errors);
                }

                $pessoa = $this->pessoaRepository->create($dataPessoa);
                $pes_id = $pessoa->pes_id;

                $dataDocumento = array(
                    'doc_pes_id' => $pes_id,
                    'doc_tpd_id' => 2,
                    'doc_conteudo' => $cpf
                );

                $this->documentoRepository->create($dataDocumento);
            }

            $dataUsuario = array(
                'usr_usuario' => $request->input('usr_usuario'),
                'usr_senha' => $request->input('usr_senha'),
                'usr_ativo' => $request->input('usr_ativo'),
                'usr_pes_id' => $pes_id
            );

            $validator = Validator::make($dataUsuario, ['usr_pes_id' => 'required|unique:seg_usuarios']);

            if ($validator->fails()) {
                flash()->error('Usuário já cadastrado');
                return redirect()->route('seguranca.usuarios.index');
            }

            $this->usuarioRepository->create($dataUsuario);

            DB::commit();

            flash()->success('Usuário criado com sucesso!');

            return redirect()->route('seguranca.usuarios.index');
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
            DB::rollback();
            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

            return redirect()->back()->with('validado', true);
        }
    }

    public function getEdit($id)
    {
        $usuario = $this->usuarioRepository->find($id);

        if (!$usuario) {
            flash()->error('Usuario não existe');
            return redirect()->back();
        }

        $pessoa = $this->pessoaRepository->findById($usuario->usr_pes_id);

        return view('Seguranca::usuarios.edit', ['usuario' => $usuario, 'pessoa' => $pessoa]);
    }

    public function putEdit($id, Request $request)
    {
        $usuarioRequest = new UsuarioRequest();
        $pessoaRequest = new PessoaRequest();

        $usuarioRules = $usuarioRequest->rules();
        $usuarioRules['usr_senha'] = 'min:4';

        $validation = Validator::make($request->all(), array_merge($usuarioRules, $pessoaRequest->rules()));

        if ($validation->fails()) {
            return redirect()->back()->withInput($request->except('usr_senha'))->withErrors($validation->messages());
        }

        DB::beginTransaction();
        try {
            $pes_id = $request->input('pes_id');

            if ($this->pessoaRepository->verifyEmail($request->input('pes_email'), $pes_id)) {
                $errors = ['pes_email' => 'Email já cadastrado'];
                return redirect()->back()->withInput($request->all())->withErrors($errors);
            }

            if ($this->documentoRepository->verifyCpf($request->input('doc_conteudo'), $pes_id)) {
                $errors = ['doc_conteudo' => 'CPF já cadastrado'];
                return redirect()->back()->withInput($request->all())->withErrors($errors);
            }

            $dataUsuario = [
                'usr_usuario' => $request->input('usr_usuario'),
                'usr_senha' => bcrypt($request->input('usr_senha')),
                'usr_ativo' => $request->input('usr_ativo')
            ];

            $this->usuarioRepository->update($dataUsuario, $id, 'usr_id');

            $dataPessoa = array(
                'pes_nome' => $request->input('pes_nome'),
                'pes_sexo' => $request->input('pes_sexo'),
                'pes_email' => strtolower($request->input('pes_email')),
                'pes_telefone' => $request->input('pes_telefone'),
                'pes_nascimento' => $request->input('pes_nascimento'),
                'pes_mae' => $request->input('pes_mae'),
                'pes_pai' => $request->input('pes_pai'),
                'pes_estado_civil' => $request->input('pes_estado_civil'),
                'pes_naturalidade' => $request->input('pes_naturalidade'),
                'pes_nacionalidade' => $request->input('pes_nacionalidade'),
                'pes_raca' => $request->input('pes_raca'),
                'pes_necessidade_especial' => $request->input('pes_necessidade_especial')
            );

            $this->pessoaRepository->update($dataPessoa, $pes_id, 'pes_id');

            $dataDocumento = [
                'doc_pes_id' => $pes_id,
                'doc_conteudo' => $request->input('doc_conteudo'),
                'doc_tpd_id' => 2
            ];

            $this->documentoRepository->updateOrCreate(['doc_pes_id' => $pes_id, 'doc_tpd_id' => 2], $dataDocumento);

            DB::commit();

            flash()->success('Usuario editado com sucesso!');
            return redirect()->route('seguranca.usuarios.index');
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect()->back()->withInput($request->except('usr_senha'))->withErrors($e);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
            DB::rollback();
            flash()->error('Erro ao tentar editar. Caso o problema persista, entre em contato com o suporte.');

            return redirect()->back();
        }
    }

    public function postDelete(Request $request)
    {
        try {
            $id = $request->get('id');

            $this->usuarioRepository->delete($id);

            flash()->success('Usuario excluído com sucesso.');
            return redirect()->back();
        } catch (\Illuminate\Database\QueryException $e) {
            flash()->error('Erro ao tentar deletar. O usuario contém dependências no sistema.');
            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->error('Erro ao tentar excluir. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }

    public function getAtribuirperfil($usuarioId)
    {
        $usuario = $this->usuarioRepository->find($usuarioId);

        if (!$usuario) {
            flash()->error('Usuario não existe!');
            return redirect()->back();
        }

        $modulos = $this->perfilRepository->getModulosWithoutPerfis($usuario->usr_id);

        return view('Seguranca::usuarios.atribuirperfil', compact('usuario', 'modulos'));
    }

    public function postAtribuirperfil($usuarioId, Request $request)
    {
        $usuario = $this->usuarioRepository->find($usuarioId);

        if (!$usuario) {
            flash()->error('Usuario não existe!');
            return redirect()->back();
        }

        $validator = Validator::make($request->all(), [
            'mod_id' => 'required',
            'prf_id' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            if (!$this->perfilRepository->verifyExistsPerfilModulo($request->input('mod_id'), $usuario->usr_id)) {
                $usuario->perfis()->attach($request->input('prf_id'));
                flash()->success('Perfil Atribuído com sucesso');
                return redirect()->back();
            }

            flash()->error('Usuario já possui perfil associado ao módulo!');
            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->error('Erro ao tentar atribuir perfil. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }

    public function postDeletarperfil($usuarioId, Request $request)
    {
        try {
            $usuario = $this->usuarioRepository->find($usuarioId);

            if (!$usuario) {
                flash()->error('Usuario não existe!');
                return redirect()->back();
            }

            $prf_id = $request->get('id');

            if ($usuario->perfis()->detach($prf_id)) {
                flash()->success('Perfil excluído com sucesso.');
                return redirect()->back();
            }

            flash()->error('Erro ao tentar excluir perfil');
            return redirect()->back();
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            } else {
                flash()->error('Erro ao tentar excluir. Caso o problema persista, entre em contato com o suporte.');

                return redirect()->back();
            }
        }
    }
}
