<?php

namespace Modulos\Academico\Http\Controllers;

use Auth;
use ActionButton;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Modulos\Academico\Http\Requests\ImportacaoRequest;
use Modulos\Academico\Http\Requests\ImportacaoUsuarioRequest;
use Modulos\Academico\Repositories\InstituicaoRepository;
use Modulos\Academico\Repositories\ProfessorRepository;
use Modulos\Academico\Repositories\TutorRepository;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Geral\Http\Requests\PessoaRequest;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Geral\Repositories\DocumentoRepository;
use Modulos\Seguranca\Models\Usuario;
use Modulos\Seguranca\Repositories\PerfilRepository;
use Modulos\Seguranca\Repositories\UsuarioRepository;

class ImportacoesUsuariosController extends BaseController
{
    protected $alunoRepository;
    protected $professorRepository;
    protected $tutorRepository;

    protected $pessoaRepository;
    protected $documentoRepository;
    protected $usuarioRepository;
    protected $perfilRepository;
    protected $instituicaoRepository;

    public function __construct(AlunoRepository $aluno,
                                ProfessorRepository $professor,
                                TutorRepository $tutor,
                                PessoaRepository $pessoa,
                                DocumentoRepository $documento,
                                UsuarioRepository $usuario,
                                PerfilRepository $perfil,
                                InstituicaoRepository $instituicao)
    {
        $this->alunoRepository = $aluno;
        $this->professorRepository = $professor;
        $this->tutorRepository = $tutor;
        $this->pessoaRepository = $pessoa;
        $this->documentoRepository = $documento;
        $this->usuarioRepository = $usuario;
        $this->perfilRepository = $perfil;
        $this->instituicaoRepository = $instituicao;
    }


    public function getIndex(Request $request)
    {
        $perfis = $this->perfilRepository->lists('prf_id', 'prf_nome');
        $instituicoes = $this->instituicaoRepository->lists('itt_id', 'itt_nome');

        return view('Academico::importacaousuario.index', compact('perfis', 'instituicoes'));

    }

    public function postImportar(ImportacaoUsuarioRequest $request)
    {
        $pessoasParaImportar = Excel::toArray(new \stdClass(), $request->file('doc_file'))[0];

        $requestData = $request->all();

        $dataPessoas = [];
        foreach ($pessoasParaImportar as $item) {
            $pessoa = array(
                'pes_nome' => $item[0],
                'pes_email' => $item[1],
                'pes_telefone' => $item[2],
                'pes_sexo' => $item[3],
                'doc_conteudo' => (string)$item[4]
            );

            $pessoaRequest = new PessoaRequest();
            $validator = Validator::make($pessoa, $pessoaRequest->rules(null, true));
            if ($validator->fails()) {
                dd($validator);
                flash()->error('Arquivo em formato inválido.');
                return redirect()->back();
            }

            $dataPessoas[] = $pessoa ;
        }

        try {
            DB::beginTransaction();
            foreach ($dataPessoas as $dataPessoa) {
                $pessoa = $this->buscaOuCriaPessoa($dataPessoa,$requestData['itt_id'] );

                $user = Usuario::where('usr_pes_id', $pessoa->pes_id)->first();

                if ($user) {
                    DB::rollback();
                    flash()->error('Usuário já cadastrado');
                    return redirect()->route('seguranca.usuarios.index');
                }
                $dataUsuario = array(
                    'usr_usuario' => $pessoa->pes_email,
                    'usr_senha' => bcrypt(env('USER_DEFAULT_PASSWORD')),
                    'usr_ativo' => 1,
                    'usr_pes_id' => $pessoa->pes_id
                );

                $usuario = $this->usuarioRepository->create($dataUsuario);

                if (isset($requestData['perfis']) && !is_null($requestData['perfis'])) {
                    foreach ($requestData['perfis'] as $id) {
                        $usuario->perfis()->attach($id);
                    }
                }
            }

        } catch (\Exception $e) {
            DB::rollback();
            if (config('app.debug')) {
                throw $e;
            }
            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }

        DB::commit();
        flash()->success('Pessoas importadas com sucesso!');
        return redirect()->back();
    }

    public function buscaOuCriaPessoa($dataPessoa,  $instituicaoId){
        $pessoa = $this->documentoRepository->buscaPessoaPeloCpf($dataPessoa['doc_conteudo']);

        if($pessoa){
            if($pessoa->pes_itt_id !== $instituicaoId ){
                flash()->error('Não é possível fazer a importação das Pessoas, contate o administrador do sistema. CPF inválido: '.$dataPessoa['doc_conteudo']);
                return redirect()->back();
            }
            return $pessoa;
        }
        $dataPessoa['pes_itt_id'] = $instituicaoId;

        $pessoa =  $this->pessoaRepository->create($dataPessoa);

        $dataDocumento = array(
            'doc_tpd_id' => 2,
            'doc_conteudo' => $dataPessoa['doc_conteudo'],
            'doc_pes_id' => $pessoa->pes_id
        );

        $this->documentoRepository->updateOrCreate(['doc_pes_id' => $pessoa->pes_id, 'doc_tpd_id' => 2], $dataDocumento);

        return $pessoa;
    }
}
