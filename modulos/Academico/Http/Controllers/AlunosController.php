<?php

namespace Modulos\Academico\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modulos\Academico\Http\Requests\AlunoRequest;
use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Geral\Http\Requests\PessoaRequest;
use Modulos\Geral\Repositories\DocumentoRepository;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Seguranca\Providers\ActionButton\TButton;
use ActionButton;
use Modulos\Seguranca\Repositories\UsuarioRepository;
use Auth;

class AlunosController extends BaseController
{
    protected $alunoRepository;
    protected $pessoaRepository;
    protected $documentoRepository;
    protected $usuarioRepository;

    public function __construct(AlunoRepository $aluno,
                                PessoaRepository $pessoa,
                                DocumentoRepository $documento,
                                UsuarioRepository $usuario)
    {
        $this->alunoRepository = $aluno;
        $this->pessoaRepository = $pessoa;
        $this->documentoRepository = $documento;
        $this->usuarioRepository = $usuario;
    }

    public function getIndex(Request $request)
    {
        $btnNovo = new TButton();
        $btnNovo->setName('Novo')->setRoute('academico.alunos.create')->setIcon('fa fa-plus')->setStyle('btn bg-olive');

        $actionButtons[] = $btnNovo;

        $paginacao = null;
        $tabela = null;

        $tableData = $this->alunoRepository->paginateRequest($request->all());

        if ($tableData->count()) {
            $tabela = $tableData->columns(array(
                'alu_id' => '#',
                'pes_nome' => 'Nome',
                'pes_email' => 'Email',
                'alu_action' => 'Ações'
            ))
                ->modifyCell('alu_action', function () {
                    return array('style' => 'width: 140px;');
                })
                ->means('alu_action', 'alu_id')
                ->modify('alu_action', function ($id) {
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
                                'route' => 'academico.alunos.edit',
                                'parameters' => ['id' => $id],
                                'label' => 'Editar',
                                'method' => 'get'
                            ],
                            [
                                'classButton' => '',
                                'icon' => 'fa fa-eye',
                                'route' => 'academico.alunos.show',
                                'parameters' => ['id' => $id],
                                'label' => 'Visualizar',
                                'method' => 'get'
                            ]
                        ]
                    ]);
                })
                ->sortable(array('alu_id', 'pes_nome'));

            $paginacao = $tableData->appends($request->except('page'));
        }

        return view('Academico::alunos.index', ['tabela' => $tabela, 'paginacao' => $paginacao, 'actionButton' => $actionButtons]);
    }

    public function getCreate(Request $request)
    {
        $pessoaId = $request->get('id');

        $alunos = $this->alunoRepository->search(array(['alu_pes_id', '=', $pessoaId]));

        if (!$alunos->isEmpty()) {
            flash()->error('Este CPF já tem um aluno cadastrado!');
            return redirect()->route('academico.alunos.index');
        }

        if (!is_null($pessoaId)) {
            $pessoa = $this->pessoaRepository->findById($pessoaId);

            if ($pessoa) {
                return view('Academico::alunos.create', ['pessoa' => $pessoa]);
            }
        }

        return view('Academico::alunos.create', ['pessoa' => []]);
    }

    public function postCreate(Request $request)
    {
        $pessoaRequest = new PessoaRequest();
        $alunoRequest = new AlunoRequest();


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

                $validator = Validator::make($request->all(), $pessoaRequest->rules($pes_id));
                if ($validator->fails()) {
                    dd("aqui");
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

                $user = Auth::user();
                $dataPessoa['pes_itt_id'] = $user->pessoa->pes_itt_id;

                $pessoa = $this->pessoaRepository->create($dataPessoa);
                $pes_id = $pessoa->pes_id;
            }

            $dataDocumento = array(
                'doc_tpd_id' => 2,
                'doc_conteudo' => $cpf,
                'doc_pes_id' => $pes_id
            );

            $this->documentoRepository->updateOrCreate(['doc_pes_id' => $pes_id, 'doc_tpd_id' => 2], $dataDocumento);

            $validator = Validator::make(['alu_pes_id' => $pes_id], $alunoRequest->rules());

            if ($validator->fails()) {
                flash()->error('Aluno já cadastrado!');
                return redirect()->route('academico.alunos.index');
            }

            $aluno = $this->alunoRepository->create(['alu_pes_id' => $pes_id]);

            $dataUsuario = array(
                'usr_usuario' => $cpf,
                'usr_senha' => $cpf,
                'usr_ativo' => 1,
                'usr_pes_id' => $pes_id
            );

            $this->usuarioRepository->create($dataUsuario);

            DB::commit();

            flash()->success('Aluno criado com sucesso!');

            return redirect()->route('academico.alunos.show', $aluno->alu_id);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
            DB::rollback();
            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');

            return redirect()->back()->with('validado', true);
        }
    }

    public function getEdit($alunoId)
    {
        $aluno = $this->alunoRepository->find($alunoId);

        if (!$aluno) {
            flash()->error('Aluno não existe.');
            return redirect()->back();
        }

        $pessoa = $this->pessoaRepository->findById($aluno->alu_pes_id);

        return view('Academico::alunos.edit', ['pessoa' => $pessoa]);
    }

    public function putEdit($pessoaId, Request $request)
    {
        $pessoaRequest = new PessoaRequest();

        $pessoa = $this->pessoaRepository->find($pessoaId);

        if (!$pessoa) {
            flash()->error('Pessoa não existe.');
            return redirect()->route('academico.alunos.index');
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

            flash()->success('Aluno editado com sucesso!');
            return redirect()->route('academico.alunos.show', $pessoa->aluno->alu_id);
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

    public function getShow($alunoId)
    {
        $aluno = $this->alunoRepository->find($alunoId);
        session(['last_acad_route' => 'academico.alunos.show', 'last_id' => $alunoId]);

        if (!$aluno) {
            flash()->error('Aluno não existe.');
            return redirect()->back();
        }

        $situacao = [
            'cursando' => 'Cursando',
            'reprovado' => 'Reprovado',
            'evadido' => 'Evadido',
            'trancado' => 'Trancado',
            'desistente' => 'Desistente'
        ];

        return view('Academico::alunos.show', ['pessoa' => $aluno->pessoa, 'aluno' => $aluno, 'situacao' => $situacao]);
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
