<?php

namespace Modulos\Integracao\Repositories;

use Harpia\Moodle\Moodle;
use Modulos\Academico\Models\MatriculaOfertaDisciplina;
use Modulos\Academico\Models\OfertaDisciplina;
use Modulos\Academico\Repositories\MatriculaOfertaDisciplinaRepository;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;
use Modulos\Academico\Repositories\PeriodoLetivoRepository;
use Modulos\Core\Repository\BaseRepository;
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
        parent::__construct($model);
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
                'ofd_tipo_avaliacao',
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

            $func = function ($value) {
                return !$value ? null : $value;
            };

            $dados = array_map($func, $dados);

            $keys = ['min_id_conceito'];

            $tipoAvaliacao = $ofertaDisciplina->ofd_tipo_avaliacao;

            if ($tipoAvaliacao == 'Conceitual') {
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

    public function mapearNotasAluno(OfertaDisciplina $ofertaDisciplina, MatriculaOfertaDisciplina $matriculaOfertaDisciplina, $configuracoesCurso)
    {
        $select = ['min_id_nota1', 'min_id_nota2', 'min_id_nota3', 'min_id_recuperacao', 'min_id_final'];

        // Buscar tipo de avaliacao da disciplina.
        $tipoAvaliacao = $ofertaDisciplina->ofd_tipo_avaliacao;
        if ($tipoAvaliacao == 'Conceitual') {
            $select = ['min_id_conceito'];
        }

        // Dependendo do tipo de avaliacao da disciplina, busca somente os ids's de itens de notas
        // necessários
        $itensNota = DB::table('int_mapeamento_itens_nota')
            ->where('min_ofd_id', $ofertaDisciplina->ofd_id)
            ->select($select)
            ->first();

        // caso não exista itens de notas cadastrados, envia uma mensagem de erro
        if (!$itensNota) {
            return array('status' => 'error', 'message' => 'Não há itens de notas cadastrados para essa oferta de disciplina.');
        }

        $itensNota = (array)$itensNota;

        $pes_id = $matriculaOfertaDisciplina->matriculaCurso->aluno->alu_pes_id;

        // Prepara o array com as informações que serão enviadas para o moodle.
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

        // Faz outra checagem pra evitar de mandar dados de itens de nota vazio.
        if (empty($itens)) {
            return array('status' => 'error', 'message' => 'Não há itens de notas cadastrados para essa oferta de disciplina.');
        }

        // Dados dos itens de notas são encapsulados em uma string json.
        $data = [
            'pes_id' => $pes_id,
            'itens' => json_encode($itens)
        ];

        $tiposenviados = [];
        foreach ($itens as $key => $tipo) {
            $tiposenviados[] = $tipo['tipo'];
        }

        $response = $this->sendDataMoodle($data, $ofertaDisciplina->ofd_trm_id);

        // Verifica se veio alguma resposta do moodle e qual status ela será encaixada.
        $status = 3;
        if ($response) {
            $status = (array_key_exists('status', $response) &&
                $response['status'] == 'success') ? 2 : 3;
        }

        // Caso tenha trazido as notas.
        if ($status == 2) {
            $arrayNotas = json_decode($response['grades'], true);

            $tiposrecebidos = [];

            foreach ($arrayNotas as $recebido) {
                if (array_key_exists('tipo', $recebido)) {
                    $tiposrecebidos[] = $recebido['tipo'];
                }
            }

            $notas = [];
            foreach ($arrayNotas as $nota) {
                $value = $nota['nota'];
                if ($nota['tipo'] != 'conceito') {
                    $value = (float)$nota['nota'];
                }
                $notas['mof_' . $nota['tipo']] = $value;
            }

            foreach ($tiposenviados as $enviado) {
                if (!in_array($enviado, $tiposrecebidos) && $enviado != 'final') {
                    $notas['mof_' . $enviado] = 0;
                }
            }

            // calcula a media final e o status de matricula do aluno
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

        if (!$ambiente) {
            return null;
        }

        // Web service de integracao
        $ambServico = $ambiente->integracao();

        if ($ambServico) {
            $parametros = [];

            // url do ambiente
            $parametros['url'] = $ambiente->amb_url;
            $parametros['token'] = $ambServico->asr_token;
            $parametros['functionname'] = 'local_integracao_get_grades_batch';
            $parametros['action'] = 'MAPEAR_NOTAS_ALUNO';

            $parametros['data']['grades'] = $data;

            $moodleService = new Moodle();

            $retorno = $moodleService->send($parametros);

            return $retorno;
        }

        return null;
    }

    public function calcularMedia(array $notas, array $configuracoesCurso, $tipoAvaliacao = 'Numérica')
    {
        if ($tipoAvaliacao == 'Conceitual') {
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

        // 1º Caso - Aluno Aprovado por Media e sem recuperacao
        if ($mediaParcial >= $mediaAprovacao) {
            $notas['mof_mediafinal'] = (float)number_format($mediaParcial, 2);
            $notas['mof_situacao_matricula'] = 'aprovado_media';
            return $notas;
        }

        // 2º Caso - Aluno nao atinge a media minima, mas possui recuperacao
        if (($mediaParcial < $mediaAprovacao) && array_key_exists('mof_recuperacao', $notas)) {
            $recuperacao = $notas['mof_recuperacao'];
            $mediaParcial = ($recuperacao > $mediaParcial) ? $recuperacao : $mediaParcial;

            $modoRecuperacao = $configuracoesCurso['modo_recuperacao'];

            if ($modoRecuperacao == 'substituir_menor_nota') {
                $menorNota = min($conjuntoNotas);

                $indice = array_search($menorNota, $conjuntoNotas);
                $conjuntoNotas[$indice] = $recuperacao;

                // recalcula a media parcial
                $mediaParcial = array_sum($conjuntoNotas) / count($conjuntoNotas);
            }

            if ($mediaParcial >= $mediaAprovacao) {
                $notas['mof_mediafinal'] = (float)number_format($mediaParcial, 2);
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

            $status = 'reprovado_final';
            if ($mediaFinal >= $mediaAprovacaoFinal) {
                $status = 'aprovado_final';
            }

            $notas['mof_mediafinal'] = (float)number_format($mediaFinal, 2);
            $notas['mof_situacao_matricula'] = $status;
            return $notas;
        }

        // 4º Caso - Aluno não atinge a media minima, e nem vai pra final
        $notas['mof_mediafinal'] = (float)number_format($mediaParcial, 2);
        $notas['mof_situacao_matricula'] = 'reprovado_media';
        return $notas;
    }
}
