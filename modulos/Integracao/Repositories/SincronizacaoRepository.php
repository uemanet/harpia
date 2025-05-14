<?php

namespace Modulos\Integracao\Repositories;

use DB;
use Modulos\Academico\Models\Matricula;
use Modulos\Academico\Models\OfertaDisciplina;
use Modulos\Academico\Models\Turma;
use Modulos\Academico\Models\TutorGrupo;
use Modulos\Core\Repository\BaseRepository;
use Modulos\Integracao\Models\Sincronizacao;
use PhpParser\Node\Expr\Array_;

class SincronizacaoRepository extends BaseRepository
{
    public function __construct(Sincronizacao $sincronizacao)
    {
        parent::__construct($sincronizacao);
    }

    public function all()
    {
        $result = $this->model;
        return $result->orderBy('created_at', 'DESC')->get();
    }

    public function updateSyncMoodle(array $data)
    {
        $keysSearch = [
            'sym_id',
            'sym_table',
            'sym_table_id',
            'sym_action',
        ];

        $query = $this->model;

        $query = $query->where('sym_status', '<>', 2);
        foreach ($keysSearch as $key) {
            if (array_key_exists($key, $data)) {
                $query = $query->where($key, '=', $data[$key]);
            }
        }

        $registros = $query->get();

        /*
         * Atualiza o ultimo registro com as especificacoes passadas
         * Os demais permanecerao com os dados anteriores
         */
        $registro = $registros->pop();
        $registro->fill($data)->save();

        return $registro->sym_id;
    }

    public function findBy(array $options)
    {
        $query = $this->model;
        if (!empty($options)) {
            foreach ($options as $key => $value) {
                $query = $query->where($key, '=', $value);
            }
            return $query->get();
        }
        return $query->all();
    }

    public function paginate($sort = null, $search = null)
    {
        $result = $this->model;

        if (!empty($search)) {
            foreach ($search as $key => $value) {
                switch ($value['type']) {
                    case 'like':
                        $result = $result->where($value['field'], $value['type'], "%{$value['term']}%");
                        break;
                    default:
                        $result = $result->where($value['field'], $value['type'], $value['term']);
                }
            }
        }

        if (empty($sort)) {
            $result = $result->orderBy('created_at', 'DESC');
        }

        if (!empty($sort)) {
            $result = $result->orderBy($sort['field'], $sort['sort']);
        }

        return $result->paginate(15);
    }

    public function getSyncData(Sincronizacao $sincronizacao): array
    {
        switch ($sincronizacao->sym_table) {
            case 'acd_ofertas_disciplinas':
                $model = OfertaDisciplina::find($sincronizacao->sym_table_id);
                $turma = $model->turma;
                $pessoa = $model->professor->pessoa;
                break;
            case 'acd_matriculas':
                $model = Matricula::find($sincronizacao->sym_table_id);
                $turma = $model->turma;
                $pessoa = $model->aluno->pessoa;
                break;
            case 'acd_tutores_grupos':
                $model = TutorGrupo::find($sincronizacao->sym_table_id);
                $turma = $model->grupo->turma;
                $pessoa = $model->tutor->pessoa;
                break;
            default:
                $turma = null;
                $pessoa = null;
        }

        $nome = explode(" ", $pessoa->pes_nome);
        $user['user']['pes_id'] = $pessoa->pes_id;
        $user['user']['firstname'] = array_shift($nome);
        $user['user']['lastname'] = implode(" ", $nome);
        $user['user']['email'] = $pessoa->pes_email;
        $user['user']['username'] = $pessoa->pes_email;
        $user['user']['city'] = $pessoa->pes_cidade;

        return ['user' => $user, 'turma' => $turma, 'pessoa' => $pessoa];
    }
}
