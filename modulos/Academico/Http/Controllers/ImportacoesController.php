<?php

namespace Modulos\Academico\Http\Controllers;

use Auth;
use ActionButton;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Modulos\Academico\Http\Requests\ImportacaoRequest;
use Modulos\Academico\Repositories\ProfessorRepository;
use Modulos\Academico\Repositories\TutorRepository;
use Modulos\Core\Http\Controller\BaseController;
use Modulos\Geral\Http\Requests\PessoaRequest;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Geral\Repositories\DocumentoRepository;
use Modulos\Seguranca\Repositories\UsuarioRepository;

class ImportacoesController extends BaseController
{
    protected $alunoRepository;
    protected $professorRepository;
    protected $tutorRepository;

    protected $pessoaRepository;
    protected $documentoRepository;
    protected $usuarioRepository;

    public function __construct(AlunoRepository $aluno,
                                ProfessorRepository $professor,
                                TutorRepository $tutor,
                                PessoaRepository $pessoa,
                                DocumentoRepository $documento,
                                UsuarioRepository $usuario)
    {
        $this->alunoRepository = $aluno;
        $this->professorRepository = $professor;
        $this->tutorRepository = $tutor;
        $this->pessoaRepository = $pessoa;
        $this->documentoRepository = $documento;
        $this->usuarioRepository = $usuario;
    }

    public function getIndex(Request $request)
    {

        $papeis = [
            'aluno' => 'Aluno',
            'professor' => 'Professor',
            'mediador' => 'Mediador'
        ];

        return view('Academico::importacao.index', compact('papeis'));

    }

    public function postImportar(ImportacaoRequest $request)
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
                flash()->error('Arquivo em formato inválido.');
                return redirect()->back();
            }

            $dataPessoas[] = $pessoa ;
        }

        $user = Auth::user();
        $instituicaoId = $user->pessoa->pes_itt_id;

        try {
            DB::beginTransaction();
            foreach ($dataPessoas as $dataPessoa) {
                $pessoa = $this->buscaOuCriaPessoa($dataPessoa,$instituicaoId );
                switch ($requestData['papel']) {
                    case "aluno":
                        $aluno = $this->alunoRepository->buscaAlunoPorIdDePessoa($pessoa->pes_id);
                        if($aluno){
                            break;
                        }
                        $this->alunoRepository->create(['alu_pes_id' => $pessoa->pes_id]);
                        break;
                    case "mediador":
                        $tutor = $this->tutorRepository->buscaTutorPorIdDePessoa($pessoa->pes_id);
                        if($tutor){
                            break;
                        }
                        $this->tutorRepository->create(['tut_pes_id' => $pessoa->pes_id]);
                        break;
                    case "professor":
                        $professor = $this->professorRepository->buscaProfessorPorIdDePessoa($pessoa->pes_id);
                        if($professor){
                            break;
                        }
                        $this->professorRepository->create(['prf_pes_id' => $pessoa->pes_id]);
                        break;
                }
            }

        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
            DB::rollback();
            flash()->error('Erro ao tentar salvar. Caso o problema persista, entre em contato com o suporte.');
            return redirect()->back();
        }

        DB::commit();
        flash()->success('Pessoas importadas com sucesso!');
        return redirect()->back();
    }

    public function validarDadosDoCsv($pessoasParaImportar){
        $dataPessoas = [];
        foreach ($pessoasParaImportar as $item) {
            $pessoa = array(
                'pes_nome' => $item[0],
                'pes_email' => $item[1],
                'pes_telefone' => $item[2],
                'pes_sexo' => $item[3],
                'doc_conteudo' => (string)$item[4]
            );
            $cpf = $item[4];

            $pessoaRequest = new PessoaRequest();
            $validator = Validator::make($pessoa, $pessoaRequest->rules(null, true));
            if ($validator->fails()) {
                flash()->error('Dados inválidos para a pessoa com cpf: '.$cpf);
                return redirect()->back();
            }

            $dataPessoas[] = $pessoa ;
        }

        return $dataPessoas;
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
