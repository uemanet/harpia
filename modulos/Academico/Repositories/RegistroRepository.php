<?php

namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\Registro;
use Modulos\Core\Model\BaseModel;
use Modulos\Core\Repository\BaseRepository;
use Auth;

class RegistroRepository extends BaseRepository
{
    protected $livroRepository;

    private $folhasLivro = 200;
    private $registrosFolha = 3;

    public function __construct(Registro $registro, LivroRepository $livroRepository)
    {
        $this->model = $registro;
        $this->livroRepository = $livroRepository;
    }

    public function create(array $data)
    {
        // Pega o ultimo registro do livro em que se esta inserindo esse registro
        $last = $this->findBy([
            'reg_liv_id' => $data['reg_liv_id']
        ])->last();

        $usuario = Auth::user()->usr_id;
        $data['reg_usuario'] = $usuario;

        // Nao ha registros
        if (!$last) {
            $data['reg_registro'] = 1;
            $data['reg_folha'] = 1;
            return $this->model->create($data);
        }

        // Calcula os novos numeros de folha e registro
        $registro = $last->reg_registro;
        $folha = $last->reg_folha;


        $registro < $this->registrosFolha ? $registro++ : $registro = 1;

        if ($this->registrosFolha($last) == $this->registrosFolha) {
            $folha++;
        }




        $data['reg_registro'] = $registro;
        $data['reg_folha'] = $folha;

        return $this->model->create($data);
    }

    public function matriculaTemRegistro($matriculaId)
    {
        $result = $this->model->where('reg_mat_id', '=', $matriculaId)->get();

        if ($result->count()) {
            return true;
        }

        return false;
    }

    public function findBy($options)
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

    private function registrosFolha(BaseModel $last)
    {
        return $this->findBy([
            'reg_liv_id' => $last->reg_liv_id,
            'reg_folha' => $last->reg_folha,
        ])->count();
    }
}
