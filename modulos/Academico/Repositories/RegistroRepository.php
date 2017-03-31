<?php

namespace Modulos\Academico\Repositories;

use Modulos\Academico\Models\Livro;
use Modulos\Academico\Models\Registro;
use Modulos\Core\Model\BaseModel;
use Modulos\Core\Repository\BaseRepository;
use Auth;

class RegistroRepository extends BaseRepository
{
    protected $livroRepository;

    const FOLHAS_LIVRO = 200;
    const REGISTROS_FOLHA = 3;

    public function __construct(Registro $registro, LivroRepository $livroRepository)
    {
        $this->model = $registro;
        $this->livroRepository = $livroRepository;
    }

    public function create(array $data)
    {
        try {
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

            $registro < self::REGISTROS_FOLHA ? $registro++ : $registro = 1;

            if ($this->registrosFolha($last) == self::REGISTROS_FOLHA) {
                $folha++;
            }

            // O livro atual esta sem espaco para registros
            if ($folha > self::FOLHAS_LIVRO) {
                $livroAtual = $this->livroRepository->find($data['reg_liv_id']);

                /* Verificar se ha um livro do mesmo tipo com espaco para registros sobrando
                 * Caso positivo, incluir no livro. Caso negativo, criar um novo livro.
                 */
                $livros = $this->livroRepository->findBy([
                    'liv_tipo_livro' => $livroAtual->liv_tipo_livro
                ]);

                foreach ($livros as $livro) {
                    if ($this->folhasLivro($livro) < self::FOLHAS_LIVRO) {
                        // Pega o ultimo registro do livro em que se esta inserindo esse registro
                        $last = $this->findBy([
                            'reg_liv_id' => $livro->liv_id
                        ])->last();

                        // Nao ha registros
                        if (!$last) {
                            $data['reg_registro'] = 1;
                            $data['reg_folha'] = 1;
                            return $this->model->create($data);
                        }

                        // Calcula os novos numeros de folha e registro
                        $registro = $last->reg_registro;
                        $folha = $last->reg_folha;

                        $registro < self::REGISTROS_FOLHA ? $registro++ : $registro = 1;

                        if ($this->registrosFolha($last) == self::REGISTROS_FOLHA) {
                            $folha++;
                        }

                        $data['reg_liv_id'] = $livro->liv_id;
                        $data['reg_registro'] = $registro;
                        $data['reg_folha'] = $folha;

                        return $this->model->create($data);
                    }
                }

                $folha = 1;
                $registro = 1;

                $novoLivroData['liv_numero'] = $livroAtual->liv_numero + 1;
                $novoLivroData['liv_tipo_livro'] = $livroAtual->liv_tipo_livro;

                $novoLivro = $this->livroRepository->create($novoLivroData);

                $data['reg_liv_id'] = $novoLivro->liv_id;
                $data['reg_registro'] = $registro;
                $data['reg_folha'] = $folha;

                return $this->model->create($data);
            }

            $data['reg_registro'] = $registro;
            $data['reg_folha'] = $folha;

            return $this->model->create($data);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
        }
    }

    public function matriculaTemRegistro($matriculaId, $moduloId)
    {
        $result = $this->model->where('reg_mat_id', '=', $matriculaId)
            ->where('reg_mdo_id', '=', $moduloId)->get();

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

    private function registrosFolha(Registro $last)
    {
        return $this->findBy([
            'reg_liv_id' => $last->reg_liv_id,
            'reg_folha' => $last->reg_folha,
        ])->count();
    }

    private function folhasLivro(Livro $livro)
    {
        $last = $this->findBy([
            'reg_liv_id' => $livro->liv_id
        ])->last();

        if ($last) {
            return $last->reg_folha;
        }

        return 0;
    }
}
