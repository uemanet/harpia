<?php

namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\ConfiguracaoCurso;
use Modulos\Core\Repository\BaseRepository;
use Modulos\Academico\Models\Curso;
use Carbon\Carbon;
use Auth;
use DB;

class CursoRepository extends BaseRepository
{
    protected $vinculoRepository;

    public function __construct(
        Curso $curso,
        VinculoRepository $vinculoRepository
    ) {
        $this->model = $curso;
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
     * Formata datas pt_BR para default MySQL
     * para update de registros
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = null)
    {
        if (!$attribute) {
            $attribute = $this->model->getKeyName();
        }

        $data['crs_data_autorizacao'] = Carbon::createFromFormat('d/m/Y', $data['crs_data_autorizacao'])->toDateString();

        $collection = $this->model->where($attribute, '=', $id)->get();

        if ($collection) {
            foreach ($collection as $obj) {
                $obj->fill($data)->save();
            }

            return $collection->count();
        }

        return 0;
    }

    /**
     * Busca um curso específico de acordo com a sua oferta
     * @param $cursodaofertaid
     * @return mixed
     */
    public function listsCursoByOferta($cursodaofertaid)
    {
        return $this->model->where('crs_id', $cursodaofertaid)->pluck('crs_nome', 'crs_id');
    }

    /**
     * Busca um curso específico de acordo com a sua matriz
     * @param $matrizId
     * @return mixed
     */
    public function listsCursoByMatriz($matrizId)
    {
        return $this->model->where('crs_id', $matrizId)->pluck('crs_nome', 'crs_id');
    }

    /**
     * Traz somente os cursos tecnicos
     * @param int $nivelTecnicoId
     * @return mixed
     */
    public function listsCursosTecnicos($nivelTecnicoId = 1)
    {
        return $this->model->where('crs_nvc_id', $nivelTecnicoId)->pluck('crs_nome', 'crs_id');
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
            } catch (\Illuminate\Database\QueryException $e) {
                DB::rollback();

                return array('status' => 'error','message' => 'Erro ao tentar deletar. O curso contém dependências no sistema.');
            } catch (\Exception $e) {
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

        if ($collection) {
            foreach ($collection as $obj) {
                $obj->delete();
            }

            return true;
        }

        return false;
    }
}
