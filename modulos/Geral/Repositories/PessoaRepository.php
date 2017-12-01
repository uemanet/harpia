<?php

namespace Modulos\Geral\Repositories;

use DB;
use Modulos\Geral\Models\Pessoa;
use Modulos\Geral\Events\UpdatePessoaEvent;
use Modulos\Core\Repository\BaseRepository;

class PessoaRepository extends BaseRepository
{
    public function __construct(Pessoa $pessoa)
    {
        parent::__construct($pessoa);
    }

    public function paginate($sort = null, $search = null)
    {
        $result = $this->model->leftJoin('gra_documentos', function ($join) {
            $join->on('pes_id', '=', 'doc_pes_id')->where('doc_tpd_id', '=', 2, 'and', true);
        });

        if (!empty($search)) {
            foreach ($search as $value) {
                if ($value['field'] == 'pes_cpf') {
                    $result = $result->where('doc_conteudo', '=', $value['term']);
                    continue;
                }

                switch ($value['type']) {
                    case 'like':
                        $result = $result->where($value['field'], $value['type'], "%{$value['term']}%");
                        break;
                    default:
                        $result = $result->where($value['field'], $value['type'], $value['term']);
                }
            }
        }

        if (!empty($sort)) {
            $result = $result->orderBy($sort['field'], $sort['sort']);
        }

        $result = $result->paginate(15);

        return $result;
    }

    public function verifyEmail($email, $idPessoa = null)
    {
        $result = $this->model->where('pes_email', $email)->get();

        if (!$result->isEmpty()) {
            if (!is_null($idPessoa)) {
                $result = $result->where('pes_id', $idPessoa);

                if (!$result->isEmpty()) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    public function update(array $data, $id, $attribute = "pes_id")
    {
        $registros = $this->model->where($attribute, '=', $id)->get();

        if ($registros) {
            foreach ($registros as $obj) {
                $obj->fill($data)->save();
            }

            return $registros->count();
        }

        return 0;
    }

    /**
     * @param $cpf
     * @return mixed
     */
    public function findPessoaByCpf($cpf)
    {
        $result = $this->model->join('gra_documentos', function ($join) {
            $join->on('pes_id', '=', 'doc_pes_id')
                ->where('doc_tpd_id', '=', 2);
        })->where('doc_conteudo', '=', $cpf)->select('pes_id')->first();

        return $result;
    }


    /**
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        $result = $this->model
            ->leftJoin('gra_documentos', function ($join) {
                $join->on('pes_id', '=', 'doc_pes_id')
                    ->where('doc_tpd_id', '=', 2);
            })
            ->select('gra_pessoas.*', 'doc_conteudo')
            ->where('pes_id', '=', $id)
            ->first();

        return $result;
    }

    public function updatePessoaAmbientes($pessoaAtt)
    {

        //verifica em quais turmas a pessoa está vinculada como professor
        $professorturmas = $this->model
            ->join('acd_professores', 'prf_pes_id', '=', 'pes_id')
            ->join('acd_ofertas_disciplinas', 'ofd_prf_id', '=', 'prf_id')
            ->where('pes_id', '=', $pessoaAtt->pes_id)
            ->groupby('ofd_trm_id')->distinct()
            ->get();

        //verifica em quais turmas a pessoa está vinculada como tutor
        $tutorgrupos = $this->model
            ->join('acd_tutores', 'tut_pes_id', '=', 'pes_id')
            ->join('acd_tutores_grupos', 'ttg_tut_id', '=', 'tut_id')
            ->where('pes_id', '=', $pessoaAtt->pes_id)
            ->groupby('ttg_grp_id')->distinct()
            ->get();

        $gruposdotutorId = [];

        foreach ($tutorgrupos as $key => $value) {
            $gruposdotutorId[] = $value->ttg_grp_id;
        }

        $tutorturmas = DB::table('acd_grupos')
            ->whereIn('grp_id', $gruposdotutorId)
            ->groupby('grp_trm_id')->distinct()
            ->get();

        //verifica em quais turmas a pessoa está vinculada como aluno
        $alunoturmas = $this->model
            ->join('acd_alunos', 'alu_pes_id', '=', 'pes_id')
            ->join('acd_matriculas', 'mat_alu_id', '=', 'alu_id')
            ->where('pes_id', '=', $pessoaAtt->pes_id)
            ->groupby('mat_trm_id')->distinct()
            ->get();

        //coloca todos os IDs de turmas que a pessoa está vinculada em um array
        $turmasdoprofessorId = [];
        $turmasdotutorId = [];
        $turmasdoalunoId = [];

        foreach ($professorturmas as $key => $value) {
            $turmasdoprofessorId[] = $value->ofd_trm_id;
        }

        foreach ($tutorturmas as $key => $value) {
            $turmasdotutorId[] = $value->grp_trm_id;
        }

        foreach ($alunoturmas as $key => $value) {
            $turmasdoalunoId[] = $value->mat_trm_id;
        }

        $turmasdapessoaId = array_merge($turmasdoprofessorId, $turmasdotutorId, $turmasdoalunoId);

        $pessoaturmas = DB::table('acd_turmas')
            ->whereIn('trm_id', $turmasdapessoaId)
            ->join('int_ambientes_turmas', 'atr_trm_id', '=', 'trm_id')
            ->get();

        $ambientesdapessoaId = [];

        foreach ($pessoaturmas as $key => $value) {
            $ambientesdapessoaId[] = $value->atr_amb_id;
        }

        $ambientesId = array_unique($ambientesdapessoaId);

        foreach ($ambientesId as $id) {
            event(new UpdatePessoaEvent($pessoaAtt, $id));
        }
    }
}
