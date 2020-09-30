<?php

namespace Modulos\Matriculas\Repositories;

use Modulos\Geral\Models\Pessoa;
use Modulos\Geral\Models\Documento;
use Modulos\Academico\Models\Aluno;
use Modulos\Matriculas\Models\Chamada;
use Modulos\Core\Repository\BaseRepository;
use Modulos\Geral\Repositories\PessoaRepository;
use Modulos\Matriculas\Models\SeletivoMatricula;
use Modulos\Academico\Repositories\AlunoRepository;
use Modulos\Geral\Repositories\DocumentoRepository;

class ChamadaRepository extends BaseRepository
{

    protected $alunoRepository;
    protected $pessoaRepository;
    protected $documentoRepository;
    protected $seletivoUserRepository;

    public function __construct(Chamada $chamada,
                                PessoaRepository $pessoaRepository,
                                DocumentoRepository $documentoRepository,
                                AlunoRepository $alunoRepository,
                                SeletivoUserRepository $seletivoUserRepository)
    {
        parent::__construct($chamada);

        $this->alunoRepository = $alunoRepository;
        $this->pessoaRepository = $pessoaRepository;
        $this->documentoRepository = $documentoRepository;
        $this->seletivoUserRepository = $seletivoUserRepository;
    }

    public function migrarAlunos(array $matriculas)
    {

        $seletivo_matriculas = SeletivoMatricula::whereIn('id', $matriculas)->get();
        $estadocivil = [
            'casado' => "casado",
            'divorciado' => "divorciado",
            'outros' => "outros",
            'solteiro' => "solteiro",
            'uniao_estavel' => "uniao_estavel"
        ];

        foreach ($seletivo_matriculas as $key => $seletivo_matricula) {
            $pessoa['pes_nome'] = $seletivo_matricula->user->nome;
            $pessoa['pes_sexo'] = $seletivo_matricula->user->sexo;
            $pessoa['pes_email'] = $seletivo_matricula->user->email;
            $pessoa['pes_telefone'] = $seletivo_matricula->user->celular ? $seletivo_matricula->user->celular : ' ';
            $pessoa['pes_nascimento'] = date("d/m/Y", strtotime($seletivo_matricula->user->nascimento));
            $pessoa['pes_mae'] = $seletivo_matricula->user->mae;
            $pessoa['pes_pai'] = $seletivo_matricula->user->pai;
            $pessoa['pes_estado_civil'] = $estadocivil[$seletivo_matricula->user->estado_civil];
            $pessoa['pes_naturalidade'] = $seletivo_matricula->user->naturalidade;
            $pessoa['pes_nacionalidade'] = $seletivo_matricula->user->nacionalidade;
            $pessoa['pes_raca'] = $seletivo_matricula->user->raca;
            $pessoa['pes_necessidade_especial'] = $seletivo_matricula->user->necessidadesespeciais;
            $pessoa['pes_estrangeiro'] = 0;
            $pessoa['pes_endereco'] = $seletivo_matricula->user->endereco;
            $pessoa['pes_numero'] = $seletivo_matricula->user->numero;
            $pessoa['pes_complemento'] = $seletivo_matricula->user->complemento;
            $pessoa['pes_cep'] = $seletivo_matricula->user->cep;
            $pessoa['pes_cidade'] = $seletivo_matricula->user->cidade;
            $pessoa['pes_bairro'] = $seletivo_matricula->user->bairro;
            $pessoa['pes_estado'] = $seletivo_matricula->user->estado;

            if (!$pessoa['pes_cep']) {
                $pessoa['pes_cep'] = '';
            }

            $pessoa['pes_cpf'] = $seletivo_matricula->user->cpf;

            $idPessoa = $this->cadastrarAluno($pessoa);

            if ($idPessoa['status'] == 'error_email') {
                $arrayHelper[] = $seletivo_matricula->user->email;
                $this->seletivoUserRepository->update(['pes_id' => 0, 'alu_id' => 0], $seletivo_matricula->user->id);
                continue;
            }

            if ($idPessoa['status'] == 'new') {
                if ($seletivo_matricula->user->rg) {
                    $rg = [
                        'doc_pes_id' => (int)$idPessoa['id'],
                        'doc_tpd_id' => 1,
                        'doc_conteudo' => $seletivo_matricula->user->rg,
                        'doc_data_expedicao' => null,
                        'doc_orgao' => null,
                        'doc_observacao' => null
                    ];

                    $this->cadastrarDocumentos((int)$idPessoa['id'], $rg);
                }

                if ($seletivo_matricula->user->cpf) {
                    $cpf = [
                        'doc_pes_id' => (int)$idPessoa['id'],
                        'doc_tpd_id' => 2,
                        'doc_conteudo' => $seletivo_matricula->user->cpf,
                        'doc_data_expedicao' => null,
                        'doc_orgao' => null,
                        'doc_observacao' => null
                    ];

                    $this->cadastrarDocumentos((int)$idPessoa['id'], $cpf);
                }


            }

            $aluno = Aluno::where('alu_pes_id', (int)$idPessoa['id'])->first();

            if (!$aluno) {

                $aluno = $this->alunoRepository->create(['alu_pes_id' => (int)$idPessoa['id']]);
                $alunoId = $aluno->alu_id;
            }
            $alunoId = $aluno->alu_id;

            $this->seletivoUserRepository->update(['pes_id' => (int)$idPessoa['id'], 'alu_id' => $alunoId], $seletivo_matricula->user->id);
            $seletivo_matricula = SeletivoMatricula::find($seletivo_matricula->id);
            $seletivo_matricula->migrado = 1;
            $seletivo_matricula->save();

        }

    }

    public function cadastrarDocumentos($idPessoa, array $data)
    {

        $data['doc_pes_id'] = $idPessoa;

        $this->documentoRepository->create($data);

    }

    public function cadastrarAluno(array $data)
    {
        $cpfHarpia = Documento::where('doc_conteudo', $data['pes_cpf'])->first();
        //se o cpf está na base do harpia, é necessário retornar o ID da pessoa que existe no acadêmico
        if ($cpfHarpia) {
            return array('status' => 'old', 'id' => $cpfHarpia->doc_pes_id);

            //se o cpf não está na base do harpia, deve-cadastrar uma nova pessoa para o aluno
        } else {

            $email = Pessoa::where('pes_email', $data['pes_email'])->first();

            if ($email) {
                return array('status' => 'error_email');
            }

            $pessoa  = $this->pessoaRepository->create($data);

            $pessoaId = $pessoa->pes_id;

            return array('status' => 'new', 'id' => $pessoaId);
        }
    }

}