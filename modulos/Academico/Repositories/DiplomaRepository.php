<?php

namespace Modulos\Academico\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\Diploma;
use Modulos\Academico\Models\Matricula;
use Harpia\Util\Util;
use DB;

class DiplomaRepository extends BaseRepository
{
    public function __construct(Diploma $diploma)
    {
        $this->model = $diploma;
    }

    public function getAlunosDiplomados($turmaId)
    {

        //recebe os alunos diplomados em deteminada turma
        $diplomados = $this->model
                           ->join('acd_matriculas', 'dip_mat_id', 'mat_id')
                           ->join('acd_turmas', 'mat_trm_id', 'trm_id')
                           ->join('acd_alunos', 'mat_alu_id', 'alu_id')
                           ->join('gra_pessoas', 'alu_pes_id', 'pes_id')
                           ->where('mat_trm_id', $turmaId)
                           ->whereNotNull('mat_data_conclusao')
                           ->orderBy('pes_nome', 'asc')
                           ->get();

        return $diplomados;
    }

    public function getPrintData($diplomas)
    {
        $retorno = [];

        foreach ($diplomas as $key => $IdDiploma) {


          //recebe livro, folha e registro da certificação
          $livfolreg = DB::table('acd_diplomas')
          ->join('acd_registros', 'reg_id', '=', 'dip_reg_id')
          ->join('acd_livros', 'liv_id', '=', 'reg_liv_id')
          ->where('dip_id', '=', $IdDiploma)
          ->first();

            if (!$livfolreg) {
                return null;
            }
            //busca a mtricula relacionada com o Id do diploma
            $matricula = Matricula::find($livfolreg->dip_mat_id);

            $conclusao = $matricula->mat_data_conclusao;
            $conclusao = strtotime(str_replace('/', '-', $matricula->mat_data_conclusao));
            $dataconclusao = new Util();
            $dataconclusao = [
              'DIA' => $dataconclusao->getDia($conclusao),
              'DIAEXTENSO' => $dataconclusao->getDiaExtenso($conclusao),
              'MES' => $dataconclusao->getMes($conclusao),
              'MESEXTENSO' => $dataconclusao->getMesExtenso($conclusao),
              'ANO' => $dataconclusao->getAno($conclusao),
            ];

            $nascimento = strtotime(str_replace('/', '-', $matricula->aluno->pessoa->pes_nascimento));
            $datanascimento = new Util();
            $datanascimento = [
              'DIA' => $datanascimento->getDia($nascimento),
              'DIAEXTENSO' => $datanascimento->getDiaExtenso($nascimento),
              'MES' => $datanascimento->getMes($nascimento),
              'MESEXTENSO' => $datanascimento->getMesExtenso($nascimento),
              'ANO' => $datanascimento->getAno($nascimento),
            ];

            $curso = Matricula::find($livfolreg->dip_mat_id)->turma->ofertacurso->curso;
            $matriz = Matricula::find($livfolreg->dip_mat_id)->turma->ofertacurso->matriz;

            $dataautorizacao = new Util();
            $dataautorizacao = [
              'DIA' => $dataautorizacao->getDia(strtotime(str_replace('/', '-', $curso->crs_data_autorizacao))),
              'DIAEXTENSO' => $dataautorizacao->getDiaExtenso(strtotime(str_replace('/', '-', $curso->crs_data_autorizacao))),
              'MES' => $dataautorizacao->getMes(strtotime(str_replace('/', '-', $curso->crs_data_autorizacao))),
              'MESEXTENSO' => $dataautorizacao->getMesExtenso(strtotime(str_replace('/', '-', $curso->crs_data_autorizacao))),
              'ANO' => $dataautorizacao->getAno(strtotime(str_replace('/', '-', $curso->crs_data_autorizacao))),
            ];

            $diaatual = new Util();
            $diaatual = [
              'DIA' => $diaatual->getDia(strtotime('today')),
              'DIAEXTENSO' => $diaatual->getDiaExtenso(strtotime('today')),
              'MES' => $diaatual->getMes(strtotime('today')),
              'MESEXTENSO' => $diaatual->getMesExtenso(strtotime('today')),
              'ANO' => $diaatual->getAno(strtotime('today')),
            ];

            $formata = str_replace('CURSO TÉCNICO EM ', '', $curso->crs_nome);
            $formata = str_replace('CURSO TÉCNICO ', '', $formata);
            $cursonome = $this->ucwords_improved(mb_strtolower($formata, "UTF-8"), array('e', 'em', 'da', 'das', 'do', 'de'));


            $returnData = [
              'EIXOCURSO' => mb_strtoupper($curso->crs_eixo, "UTF-8"),
              'HABILITAÇÂO' => mb_strtoupper($curso->crs_habilitacao, "UTF-8") ,
              'REGISTRO' => str_pad($livfolreg->reg_registro, 2, '0', STR_PAD_LEFT),
              'LIVRO' => str_pad($livfolreg->liv_numero, 2, '0', STR_PAD_LEFT),
              'FOLHA' => str_pad($livfolreg->reg_folha, 2, '0', STR_PAD_LEFT),
              'PROCESSO' => $livfolreg->dip_processo,
              'OBSERVACAO' => "Curso de Educação profissional técnica de nível médio - Subsequente",
              'DIRETORCENTRO'=> mb_strtoupper($curso->centro->diretor->pessoa->pes_nome, "UTF-8"),
              'MATRICULADIRETORCENTRO'=> $curso->centro->diretor->prf_codigo,
              'DIRETORCURSO'=> mb_strtoupper($curso->diretor->pessoa->pes_nome, "UTF-8"),
              'MATRICULADIRETORCURSO'=> $curso->diretor->prf_codigo,
              'REGISTROSISTEC'=> $livfolreg->dip_codigo_autenticidade_externo,
              'CHPRATICA'=> $matriz->mtc_horas_praticas,
              'CHTOTAL'=> $matriz->mtc_horas,
              'CENTRO' => $curso->centro->cen_nome,
              'CURSO' => $cursonome,
              'NACIONALIDADE' => $matricula->aluno->pessoa->pes_nacionalidade,
              'NATURALIDADE' => $matricula->aluno->pessoa->pes_naturalidade,
              'NASCIMENTO' => $datanascimento,
              'NOME' => $matricula->aluno->pessoa->pes_nome,
              'IDENTIDADE' => $matricula->aluno->pessoa->documentos->where('doc_tpd_id', 1)->first()->doc_conteudo,
              'ORGAO' => str_replace('/', '-', $matricula->aluno->pessoa->documentos->where('doc_tpd_id', 1)->first()->doc_orgao),
              'CONCLUSAO' => $dataconclusao,
              'RESOLUCAO' => $curso->crs_resolucao,
              'DATA_AUTORIZACAO' => $dataautorizacao,
              'DIAATUAL' => $diaatual
            ];

            foreach ($returnData as $key => $dado) {
                if (!$dado) {
                    return array('type' => 'error' , 'dados' => $returnData, 'campo' => $key);
                }
            }

            $retorno[] = $returnData;
        }

        return $retorno;
    }
    public function ucwords_improved($s, $e = array())
    {
        return join(' ', array_map(create_function('$s', 'return (!in_array($s, '.var_export($e, true) . ')) ? ucfirst($s) : $s;'), explode(' ', strtolower($s))));
    }
}
