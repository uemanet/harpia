<?php

namespace Modulos\Integracao\Repositories;

use Harpia\Moodle\Moodle;
use Modulos\Academico\Repositories\MatriculaOfertaDisciplinaRepository;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;
use Modulos\Academico\Repositories\PeriodoLetivoRepository;
use Modulos\Core\Repository\BaseRepository;
use Modulos\Integracao\Events\AtualizarSyncEvent;
use Modulos\Integracao\Events\MapearNotasEvent;
use Modulos\Integracao\Models\MapeamentoNota;
use DB;

class MapeamentoNotasRepository extends BaseRepository
{
    protected $periodoLetivoRepository;
    protected $ofertaDisciplinaRepository;
    protected $matriculaOfertaDisciplinaRepository;
    protected $ambienteVirtualRepository;

    public function __construct(
        MapeamentoNota $model,
        PeriodoLetivoRepository $periodoLetivoRepository,
        OfertaDisciplinaRepository $ofertaDisciplinaRepository,
        MatriculaOfertaDisciplinaRepository $matriculaOfertaDisciplinaRepository,
        AmbienteVirtualRepository $ambienteVirtualRepository
    ) {
        $this->model = $model;
        $this->periodoLetivoRepository = $periodoLetivoRepository;
        $this->ofertaDisciplinaRepository = $ofertaDisciplinaRepository;
        $this->matriculaOfertaDisciplinaRepository = $matriculaOfertaDisciplinaRepository;
        $this->ambienteVirtualRepository = $ambienteVirtualRepository;
    }

    public function getGradeCurricularByTurma($turmaId)
    {
        $periodos = $this->periodoLetivoRepository->getAllByTurma($turmaId);

        $returndata = array();

        foreach ($periodos as $periodo) {
            $reg = array();

            $reg['per_id'] = $periodo->per_id;
            $reg['per_nome'] = $periodo->per_nome;

            $ofertas = $this->ofertaDisciplinaRepository->findAllWithMapeamentoNotas([
                'ofd_trm_id' => $turmaId,
                'ofd_per_id' => $periodo->per_id
            ], [
                'ofd_id',
                'mdc_tipo_avaliacao',
                'dis_nome',
                'min_id_nota1',
                'min_id_nota2',
                'min_id_nota3',
                'min_id_conceito',
                'min_id_recuperacao',
                'min_id_final'
            ], ['dis_nome' => 'asc']);

            if ($ofertas->count()) {
                $reg['ofertas'] = $ofertas;

                $returndata[] = $reg;
            }
        }

        return $returndata;
    }

    public function setMapeamentoNotas($dados)
    {
        try {
            $ofertaId = $dados['min_ofd_id'];

            $ofertaDisciplina = $this->ofertaDisciplinaRepository->find($ofertaId);

            if (!$ofertaDisciplina) {
                return array('error' => 'Oferta de Disciplina não existe!');
            }

            $moduloDisciplina = $ofertaDisciplina->moduloDisciplina;

            $func = function ($value) {
                return !$value ? null : $value;
            };

            $dados = array_map($func, $dados);

            $keys = ['min_id_conceito'];

            $tipoAvaliacao = lcfirst($moduloDisciplina->mdc_tipo_avaliacao);

            if ($tipoAvaliacao == 'conceitual') {
                $keys = ['min_id_nota1', 'min_id_nota2', 'min_id_nota3', 'min_id_recuperacao', 'min_id_final'];
            }

            foreach ($keys as $key) {
                unset($dados[$key]);
            }

            $mapeamento = $this->model->where('min_ofd_id', '=', $dados['min_ofd_id'])->first();

            if (!$mapeamento) {
                $this->model->create($dados);
                return array('msg' => "Itens de notas mapeadas com sucesso!");
            }

            $mapeamento->fill($dados);
            $mapeamento->save();

            return array('msg' => "Itens de notas atualizadas com sucesso!");
        } catch (\Exception $e) {
            return array('error' => 'Erro ao tentar salvar/atualizar itens de notas. Entra em contato com o suporte');
        }
    }

    public function mapearNotasAluno($mof_id)
    {
        $matriculaOfertaDisciplina = $this->matriculaOfertaDisciplinaRepository->find($mof_id);

        if (!$matriculaOfertaDisciplina) {
            return array('status' => 'error', 'message' => 'Matricula na Oferta de Disciplina não existe.');
        }

        $select = ['min_id_nota1', 'min_id_nota2', 'min_id_nota3', 'min_id_recuperacao', 'min_id_final'];

        // buscar tipo de avaliacao da disciplina
        $tipoAvaliacao = lcfirst($matriculaOfertaDisciplina->ofertaDisciplina->moduloDisciplina->mdc_tipo_avaliacao);
        if ($tipoAvaliacao == 'conceitual') {
            $select = ['min_id_conceito'];
        }

        $itensNota = DB::table('int_mapeamento_itens_nota')
                        ->where('min_ofd_id', $matriculaOfertaDisciplina->mof_ofd_id)
                        ->select($select)
                        ->first();

        if (!$itensNota) {
            return array('status' => 'error', 'message' => 'Não há itens de notas cadastrados para essa oferta de disciplina.');
        }

        $itensNota = (array)$itensNota;

        $pes_id = $matriculaOfertaDisciplina->matriculaCurso->aluno->alu_pes_id;

        $itens = [];
        foreach ($itensNota as $key => $value) {
            if ($value) {
                $tipo = str_replace('min_id_', '', $key);
                $itens[] = [
                    'id' => $value,
                    'tipo' => $tipo
                ];
            }
        }

        if (empty($itens)) {
            return array('status' => 'error', 'message' => 'Não há itens de notas cadastrados para essa oferta de disciplina.');
        }

        $data = [
            'pes_id' => $pes_id,
            'itens' => json_encode($itens)
        ];

        $trm_id = $matriculaOfertaDisciplina->matriculaCurso->mat_trm_id;

        event(new MapearNotasEvent($matriculaOfertaDisciplina, 'MAPEAR_NOTAS_ALUNO'));

        $response = $this->sendDataMoodle($data, $trm_id);

        $status = 3;
        if ($response) {
            $status = (array_key_exists('status', $response) &&
                $response['status'] == 'success') ? 2 : 3;

            event(new AtualizarSyncEvent($matriculaOfertaDisciplina, $status, $response['message'], 'MAPEAR_NOTAS_ALUNO'));
        }

        if ($status == 2) {
            $arrayNotas = json_decode($response['grades'], true);

            if (empty($arrayNotas)) {
                return array('status' => 'error', 'message' => 'Aluno não possui notas na oferta de disciplina.');
            }
            $notas = [];
            foreach ($arrayNotas as $nota) {
                $value = $nota['nota'];
                if ($nota['tipo'] != 'conceito') {
                    $value = (float)$nota['nota'];
                }
                $notas['mof_'.$nota['tipo']] = $value;
            }

            $cursoId = $matriculaOfertaDisciplina->matriculaCurso->turma->ofertaCurso->ofc_crs_id;
            $configuracoesCurso = $this->getConfiguracoesCurso($cursoId);

            $notas = $this->calcularMedia($notas, $configuracoesCurso, $tipoAvaliacao);

            // atualizar o registro de notas
            foreach ($notas as $key => $value) {
                $matriculaOfertaDisciplina->{$key} = $value;
            }
            $matriculaOfertaDisciplina->save();

            return array('status' => 'success', 'message' => 'Notas mapeadas com sucesso.');
        }

        return array('status' => 'error', 'message' => 'Erro na comunicação com o web service no Moodle. Entre em contato com o suporte.');
    }

    private function sendDataMoodle(array $data, $trm_id)
    {
        // buscar ambiente virtual vinculado à turma do aluno
        $ambiente = $this->ambienteVirtualRepository->getAmbienteByTurma($trm_id);

        if ($ambiente) {
            $parametros = [];

            // url do ambiente
            $parametros['url'] = $ambiente->url;
            $parametros['token'] = $ambiente->token;
            $parametros['functioname'] = 'local_integracao_get_grades_batch';
            $parametros['action'] = 'MAPEAR_NOTAS_ALUNO';

            $parametros['data']['grades'] = $data;

            $moodleService = new Moodle();

            $retorno = $moodleService->send($parametros);

            return $retorno;
        }

        return null;
    }

    private function getConfiguracoesCurso($cursoId)
    {
        return DB::table('acd_configuracoes_cursos')
                        ->where('cfc_crs_id', '=', $cursoId)
                        ->pluck('cfc_valor', 'cfc_nome')
                        ->toArray();
    }

    public function calcularMedia(array $notas, array $configuracoesCurso, $tipoAvaliacao = 'numerica')
    {
        if ($tipoAvaliacao == 'conceitual') {
            $conceitosAprovacao = json_decode($configuracoesCurso['conceitos_aprovacao'], true);

            $situacaoMatricula = 'reprovado_media';
            if (in_array($notas['mof_conceito'], $conceitosAprovacao)) {
                $situacaoMatricula = 'aprovado_media';
            }

            $notas['mof_situacao_matricula'] = $situacaoMatricula;

            return $notas;
        }

        // calcular a media das notas
        $conjuntoNotas = [];
        foreach ($notas as $key => $value) {
            if (in_array($key, ['mof_nota1', 'mof_nota2', 'mof_nota3'])) {
                $conjuntoNotas[] = $value;
            }
        }

        $mediaAprovacao = (float)$configuracoesCurso['media_min_aprovacao'];

        $mediaParcial = array_sum($conjuntoNotas) / count($conjuntoNotas);
        $mediaParcial = round($mediaParcial, 1);

        // 1º Caso - Aluno Aprovado por Media e sem recuperacao
        if ($mediaParcial >= $mediaAprovacao) {
            $notas['mof_mediafinal'] = $mediaParcial;
            $notas['mof_situacao_matricula'] = 'aprovado_media';
            return $notas;
        }

        // 2º Caso - Aluno nao atinge a media minima, mas possui recuperacao
        if (($mediaParcial < $mediaAprovacao) && array_key_exists('mof_recuperacao', $notas)) {
            $recuperacao = $notas['mof_recuperacao'];
            $mediaParcial =  ($recuperacao > $mediaParcial) ? $recuperacao : $mediaParcial;

            $modoRecuperacao = $configuracoesCurso['modo_recuperacao'];

            if ($modoRecuperacao == 'substituir_menor_nota') {
                $menorNota = min($conjuntoNotas);

                $indice = array_search($menorNota, $conjuntoNotas);
                $conjuntoNotas[$indice] = $recuperacao;

                // recalcula a media parcial
                $mediaParcial = array_sum($conjuntoNotas) / count($conjuntoNotas);
                $mediaParcial = round($mediaParcial, 1);
            }

            if ($mediaParcial >= $mediaAprovacao) {
                $notas['mof_mediafinal'] = $mediaParcial;
                $notas['mof_situacao_matricula'] = 'aprovado_media';
                return $notas;
            }
        }

        // 3º caso - Aluno não atinge a media minima, não passa na recuperação, mas pode ir pra final
        $mediaMinFinal = (float)$configuracoesCurso['media_min_final'];
        if (($mediaMinFinal <= $mediaParcial) && ($mediaParcial < $mediaAprovacao) && array_key_exists('mof_final', $notas)) {
            $mediaAprovacaoFinal = (float)$configuracoesCurso['media_min_aprovacao_final'];

            $notaFinal = $notas['mof_final'];

            $mediaFinal = ($mediaParcial + $notaFinal) / 2;
            $mediaFinal = round($mediaFinal, 1);

            $status = 'reprovado_final';
            if ($mediaFinal >= $mediaAprovacaoFinal) {
                $status = 'aprovado_final';
            }

            $notas['mof_mediafinal'] = $mediaFinal;
            $notas['mof_situacao_matricula'] = $status;
            return $notas;
        }

        // 4º Caso - Aluno não atinge a media minima, e nem vai pra final
        $notas['mof_mediafinal'] = $mediaParcial;
        $notas['mof_situacao_matricula'] = 'reprovado_media';
        return $notas;
    }
}
