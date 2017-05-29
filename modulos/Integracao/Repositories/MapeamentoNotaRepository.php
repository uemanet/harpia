<?php

namespace Modulos\Integracao\Repositories;

use Modulos\Academico\Repositories\MatriculaOfertaDisciplinaRepository;
use Modulos\Academico\Repositories\OfertaDisciplinaRepository;
use Modulos\Academico\Repositories\PeriodoLetivoRepository;
use Modulos\Core\Repository\BaseRepository;
use Modulos\Integracao\Events\MapearNotasEvent;
use Modulos\Integracao\Models\MapeamentoNota;
use DB;

class MapeamentoNotaRepository extends BaseRepository
{
    protected $periodoLetivoRepository;
    protected $ofertaDisciplinaRepository;
    protected $matriculaOfertaDisciplinaRepository;

    public function __construct(
        MapeamentoNota $model,
        PeriodoLetivoRepository $periodoLetivoRepository,
        OfertaDisciplinaRepository $ofertaDisciplinaRepository,
        MatriculaOfertaDisciplinaRepository $matriculaOfertaDisciplinaRepository
    ) {
        $this->model = $model;
        $this->periodoLetivoRepository = $periodoLetivoRepository;
        $this->ofertaDisciplinaRepository = $ofertaDisciplinaRepository;
        $this->matriculaOfertaDisciplinaRepository = $matriculaOfertaDisciplinaRepository;
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

            if ($moduloDisciplina->mdc_tipo_avaliacao == 'conceitual') {
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

        //event(new MapearNotasEvent($matriculaOfertaDisciplina, 'MAPEAR_NOTAS_ALUNO'));

        $select = ['min_id_nota1', 'min_id_nota2', 'min_id_nota3', 'min_id_recuperacao', 'min_id_final'];

        // buscar tipo de avaliacao da disciplina
        $tipoAvaliacao = $matriculaOfertaDisciplina->ofertaDisciplina->moduloDisciplina->mdc_tipo_avaliacao;
        if ($tipoAvaliacao == 'conceitual') {
            $select = ['min_id_conceito'];
        }

        $itensNota = DB::table('int_mapeamento_itens_nota')
                        ->where('min_ofd_id', $mof_id)
                        ->select($select)
                        ->first();

        $itensNota = (array)$itensNota;

        $pes_id = $matriculaOfertaDisciplina->matriculaCurso->aluno->alu_pes_id;

        $itens = [];
        foreach ($itensNota as $key => $value) {
            if (!is_null($value)) {
                $itens[] = $value;
            }
        }

        $data = [
            'pes_id' => $pes_id,
            'itens' => json_encode($itens)
        ];

        $this->sendDataMoodle($data);
    }

    private function sendDataMoodle($data)
    {
    }
}
