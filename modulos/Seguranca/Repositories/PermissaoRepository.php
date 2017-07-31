<?php

namespace Modulos\Seguranca\Repositories;

use Modulos\Core\Repository\BaseRepository;
use Modulos\Seguranca\Models\Permissao;

class PermissaoRepository extends BaseRepository
{
    public function __construct(Permissao $model)
    {
        parent::__construct($model);
    }

    public function getRecursosByModulo($modulo)
    {
        $permissoes = $this->model->all();

        $permissoes = $permissoes->filter(function ($obj) use($modulo) {
            return $obj->slugModulo() == $modulo;
        })->all();

        $recursos = [];

        $nomeRecurso = '';
        foreach ($permissoes as $permissao) {

            if ($permissao->recurso() != $nomeRecurso) {
                $recursos[$permissao->recurso()] = $permissao->recurso();
                $nomeRecurso = $permissao->recurso();
            }
        }

        return $recursos;
    }

    public function create(array $data)
    {
        try {
            $rota = $data['modulo'].'.'.$data['recurso'].'.'.$data['prm_nome'];

            $exists = $this->model->where('prm_rota', $rota)->where('prm_nome',$data['prm_nome'])->first();

            if ($exists) {
                return array('status' => 'error', 'message' => 'Permissão já existe.');
            }

            $this->model->create([
                'prm_nome' => $data['prm_nome'],
                'prm_rota' => $rota,
                'prm_descricao' => $data['prm_descricao']
            ]);

            return array('status' => 'success', 'message' => 'Permissão criada com sucesso.');
        } catch (\Exception $e) {

            return array('status' => 'error', 'message' => 'Erro ao tentar criar permissao. Entrar em contato com o suporte.');
        }
    }

    public function update(array $data, $id, $attribute = null)
    {
        if (!$attribute) {
            $attribute = $this->model->getKeyName();
        }

        $collection = $this->model->where($attribute, '=', $id)->get();

        $rota = $data['modulo'].'.'.$data['recurso'].'.'.$data['prm_nome'];

        if ($collection) {
            foreach ($collection as $obj) {
                $obj->fill([
                    'prm_nome' => $data['prm_nome'],
                    'prm_rota' => $rota,
                    'prm_descricao' => $data['prm_descricao']
                ])->save();
            }

            return $collection->count();
        }

        return 0;
    }
}