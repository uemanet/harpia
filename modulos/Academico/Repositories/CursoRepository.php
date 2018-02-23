<?php

namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\ConfiguracaoCurso;
use Modulos\Academico\Models\Vinculo;
use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\Curso;
use Auth;
use DB;

class CursoRepository extends BaseRepository
{
    protected $vinculoRepository;

    public function __construct(
        Curso $curso,
        VinculoRepository $vinculoRepository
    ) {
        parent::__construct($curso);
        $this->vinculoRepository = $vinculoRepository;
    }

    /**
     * @param $identifier
     * @param $field
     * @return mixed
     */
    public function lists($identifier, $field, $all = false)
    {
        if (!$all) {
            return $this->model
                ->join('acd_usuarios_cursos', 'ucr_crs_id', '=', 'crs_id')
                ->where('ucr_usr_id', '=', Auth::user()->usr_id)
                ->pluck($field, $identifier)->toArray();
        }

        return $this->model->pluck($field, $identifier)->toArray();
    }

    public function listsByCursoId($cursoId)
    {
        return $this->model
            ->where('crs_id', $cursoId)
            ->pluck('crs_nome', 'crs_id');
    }

    /**
     * Busca um curso específico de acordo com a sua matriz
     * @param $matrizId
     * @return mixed
     */
    public function listsByMatrizId($matrizId)
    {
        return DB::table('acd_matrizes_curriculares')
                      ->join('acd_cursos', 'mtc_crs_id', 'crs_id')
                      ->where('mtc_id', $matrizId)
                      ->pluck('crs_nome', 'crs_id');
    }

    /**
     * Traz somente os cursos tecnicos
     * @param int $nivelTecnicoId
     * @param bool $all
     * @return mixed
     */
    public function listsCursosTecnicos($nivelTecnicoId = 2, $all = false)
    {
        if (!$all) {
            return $this->model
                ->join('acd_usuarios_cursos', 'ucr_crs_id', '=', 'crs_id')
                ->where('ucr_usr_id', '=', Auth::user()->usr_id)
                ->where('crs_nvc_id', '=', $nivelTecnicoId)
                ->pluck('crs_nome', 'crs_id')->toArray();
        }

        return $this->model
            ->where('crs_nvc_id', '=', $nivelTecnicoId)
            ->pluck('crs_nome', 'crs_id')->toArray();
    }

    public function create(array $data)
    {
        try {
            DB::beginTransaction();

            $dataCurso = [
                'crs_cen_id' => $data['crs_cen_id'],
                'crs_nvc_id' => $data['crs_nvc_id'],
                'crs_prf_diretor' => $data['crs_prf_diretor'],
                'crs_nome' => $data['crs_nome'],
                'crs_sigla' => $data['crs_sigla'],
                'crs_descricao' => $data['crs_descricao'],
                'crs_resolucao' => $data['crs_resolucao'],
                'crs_autorizacao' => $data['crs_autorizacao'],
                'crs_data_autorizacao' => $data['crs_data_autorizacao'],
                'crs_eixo' => $data['crs_eixo'],
                'crs_habilitacao' => $data['crs_habilitacao']
            ];

            $curso = $this->model->create($dataCurso);

            $dataConfiguracoes = [
                'media_min_aprovacao' => $data['media_min_aprovacao'],
                'media_min_final' => $data['media_min_final'],
                'media_min_aprovacao_final' => $data['media_min_aprovacao_final'],
                'modo_recuperacao' => $data['modo_recuperacao'],
                'conceitos_aprovacao' => json_encode($data['conceitos_aprovacao'], JSON_UNESCAPED_UNICODE)
            ];

            foreach ($dataConfiguracoes as $key => $value) {
                $curso->configuracoes()->save(
                    new ConfiguracaoCurso([
                        'cfc_nome' => $key,
                        'cfc_valor' => $value
                    ])
                );
            }

            Vinculo::create([
                'ucr_usr_id' => Auth::user()->usr_id,
                'ucr_crs_id' => $curso->getKey(),
            ]);

            DB::commit();

            return array('status' => 'success', 'message' => 'Curso criado com sucesso.');
        } catch (\Illuminate\Database\QueryException | \Exception $e) {
            DB::rollback();

            if (config('app.debug')) {
                throw $e;
            }
            return array('status' => 'error', 'message' => 'Erro ao criar curso. Entrar em contato com o suporte.');
        }
    }

    public function updateCurso(array $data, $id)
    {
        $curso = $this->model->find($id);

        if (!$curso) {
            return array('status' => 'error', 'message' => 'Curso não existe.');
        }

        try {
            DB::beginTransaction();

            $dataCurso = [
                'crs_cen_id' => $data['crs_cen_id'],
                'crs_nvc_id' => $data['crs_nvc_id'],
                'crs_prf_diretor' => $data['crs_prf_diretor'],
                'crs_nome' => $data['crs_nome'],
                'crs_sigla' => $data['crs_sigla'],
                'crs_descricao' => $data['crs_descricao'],
                'crs_resolucao' => $data['crs_resolucao'],
                'crs_autorizacao' => $data['crs_autorizacao'],
                'crs_data_autorizacao' => $data['crs_data_autorizacao'],
                'crs_eixo' => $data['crs_eixo'],
                'crs_habilitacao' => $data['crs_habilitacao']
            ];

            $curso->fill($dataCurso)->save();

            $dataConfiguracoes = [
                'media_min_aprovacao' => $data['media_min_aprovacao'],
                'media_min_final' => $data['media_min_final'],
                'media_min_aprovacao_final' => $data['media_min_aprovacao_final'],
                'modo_recuperacao' => $data['modo_recuperacao'],
                'conceitos_aprovacao' => json_encode($data['conceitos_aprovacao'], JSON_UNESCAPED_UNICODE)
            ];

            foreach ($dataConfiguracoes as $key => $value) {
                $configuracao = $curso->configuracoes()->where('cfc_nome', '=', $key)->first();

                $configuracao->fill([
                    'cfc_valor' => $value
                ])->save();
            }

            DB::commit();

            return array('status' => 'success', 'message' => 'Curso atualizado com sucesso.');
        } catch (\Illuminate\Database\QueryException | \Exception $e) {
            DB::rollback();

            if (config('app.debug')) {
                throw $e;
            }

            return array('status' => 'error', 'message' => 'Erro ao editar curso. Parâmetros devem estar errados.');
        }
    }

    public function delete($id)
    {
        $curso = $this->find($id);

        if ($curso) {
            try {
                DB::beginTransaction();

                $this->vinculoRepository->deleteAllVinculosByCurso($id);

                $this->deleteConfiguracoes($id);

                $curso->delete();

                DB::commit();

                return array('status' => 'success', 'message' => 'Curso excluído com sucesso.');
            } catch (\Illuminate\Database\QueryException | \Exception $e) {
                DB::rollback();

                if (config('app.debug')) {
                    throw $e;
                }

                return array('status' => 'error', 'message' => 'Erro ao tentar excluir. Caso o problema persista, entre em contato com o suporte.');
            }
        }

        return array('status' => 'error', 'message' => 'Curso não existe.');
    }

    public function deleteConfiguracoes($cursoId)
    {
        $collection = ConfiguracaoCurso::where('cfc_crs_id', '=', $cursoId)->get();

        if ($collection->count()) {
            foreach ($collection as $obj) {
                $obj->delete();
            }

            return true;
        }

        return false;
    }

    public function getCursosPorNivel()
    {
        return DB::table('acd_cursos')
            ->select('nvc_nome', 'crs_nvc_id', DB::raw("COUNT(*) as quantidade"))
            ->join('acd_niveis_cursos', 'crs_nvc_id', '=', 'nvc_id')
            ->groupBy('crs_nvc_id')->get()->toArray();
    }

    public function getCursosByAmbiente($ambienteId)
    {
        return DB::table('int_ambientes_turmas')
                  ->select('crs_nome', 'crs_id')
                  ->join('acd_turmas', 'atr_trm_id', '=', 'trm_id')
                  ->join('acd_ofertas_cursos', 'trm_ofc_id', '=', 'ofc_id')
                  ->join('acd_cursos', 'ofc_crs_id', '=', 'crs_id')
                  ->where('atr_amb_id', $ambienteId)
                  ->orderBy('crs_nome')
                  ->distinct('crs_nome')->pluck('crs_nome', 'crs_id');
    }
}
