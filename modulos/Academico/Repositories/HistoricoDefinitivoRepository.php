<?php

namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\Matricula;
use Modulos\Core\Repository\BaseRepository;
use Modulos\Geral\Repositories\TitulacaoInformacaoRepository;

class HistoricoDefinitivoRepository extends BaseRepository
{
    private $matriculaOfertaDisciplinaRepository;
    private $lancamentoTccRepository;
    private $titulacaoInformacaoRepository;

    public function __construct(
        Matricula $model,
        MatriculaOfertaDisciplinaRepository $matriculaOfertaDisciplinaRepository,
        LancamentoTccRepository $lancamentoTccRepository,
        TitulacaoInformacaoRepository $titulacaoInformacaoRepository
    ) {
        $this->model = $model;
        $this->matriculaOfertaDisciplinaRepository = $matriculaOfertaDisciplinaRepository;
        $this->lancamentoTccRepository = $lancamentoTccRepository;
        $this->titulacaoInformacaoRepository = $titulacaoInformacaoRepository;
    }

    public function getGradeCurricularByMatricula($matriculaId)
    {
        $matricula = $this->model->find($matriculaId);

        $returndata = array();

        $curso = $matricula->turma->ofertacurso->curso;

        $turma = $matricula->turma;
        $turma->periodo_letivo = $turma->periodo->per_nome;

        $returndata['curso'] = $curso;
        $returndata['turma'] = $turma;

        $pessoa['nome'] = $matricula->aluno->pessoa->pes_nome;

        $rg = $matricula->aluno->pessoa->documentos()->where('doc_tpd_id', 1)->first();
        $cpf = $matricula->aluno->pessoa->documentos()->where('doc_tpd_id', 2)->first();

        $pessoa['rg'] = [
            'conteudo' => $rg->doc_conteudo,
            'orgao' => $rg->doc_orgao
        ];

        $pessoa['cpf'] = $cpf->doc_conteudo;

        $pessoa['nascimento'] = $matricula->aluno->pessoa->pes_nascimento;
        $pessoa['naturalidade'] = $matricula->aluno->pessoa->pes_naturalidade;
        $pessoa['nacionalidade'] = $matricula->aluno->pessoa->pes_nacionalidade;
        $pessoa['matricula'] = $matricula->mat_id;

        $returndata['pessoa'] = $pessoa;

        $disciplinas = $this->matriculaOfertaDisciplinaRepository->findBy(
            ['mof_mat_id' => $matricula->mat_id],
            ['mof_id', 'mof_nota1', 'mof_nota2', 'mof_nota3', 'mof_conceito', 'mof_recuperacao', 'mof_final',
                'mof_mediafinal', 'mof_situacao_matricula', 'mdo_id', 'mdo_nome', 'mdo_descricao', 'mdo_qualificacao',
                'dis_nome', 'dis_carga_horaria', 'dis_creditos', 'pes_id', 'pes_nome as professor'],
            ['mdo_id' => 'asc', 'dis_nome' => 'asc']
        );

        for ($i = 0; $i < $disciplinas->count(); $i++) {
            $titulacao = null;
            $disciplinas[$i]->professor_titulacao = '';

            $result = $this->titulacaoInformacaoRepository->findBy(
                ['tin_pes_id' => $disciplinas[$i]->pes_id],
                null,
                ['tit_peso' => 'desc']
            );

            if ($result) {
                $titulacao = $result->first();

                if ($titulacao->tit_id == 2) {
                    $disciplinas[$i]->professor_titulacao = 'Graduado';
                } elseif ($titulacao->tit_id == 3) {
                    $disciplinas[$i]->professor_titulacao = 'Especialista';
                } elseif ($titulacao->tit_id == 4) {
                    $disciplinas[$i]->professor_titulacao = 'Mestre';
                } elseif ($titulacao->tit_id == 5) {
                    $disciplinas[$i]->professor_titulacao = 'Doutor';
                } elseif ($titulacao->tit_id == 6) {
                    $disciplinas[$i]->professor_titulacao = 'Pós-Doutor';
                } elseif ($titulacao->tit_id == 7) {
                    $disciplinas[$i]->professor_titulacao = 'Pós-Graduado';
                }
            }
        }

        setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');

        $returndata['data'] = 'São Luís, '.strftime('%d de %B de %Y', strtotime('today'));

        if ($curso->crs_nvc_id == 1) {
            $returndata['disciplinas'] = $disciplinas->groupBy('mdo_nome');

            return $returndata;
        }

        $returndata['disciplinas'] = $disciplinas;

        $returndata['tcc'] = $this->lancamentoTccRepository->findBy(
            ['ltc_id' => $matricula->mat_ltc_id],
            ['ltc_titulo', 'ltc_tipo', 'ltc_data_apresentacao', 'ltc_observacao']
        )->first();

        return $returndata;
    }
}
