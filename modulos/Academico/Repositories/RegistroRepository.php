<?php
declare(strict_types=1);

namespace Modulos\Academico\Repositories;

use App;
use Auth;
use Ramsey\Uuid\Uuid;
use Modulos\Academico\Models\Livro;
use Modulos\Academico\Models\Diploma;
use Modulos\Academico\Models\Registro;
use Modulos\Academico\Models\Certificado;
use Modulos\Core\Repository\BaseRepository;

class RegistroRepository extends BaseRepository
{
    protected $livroRepository;

    const FOLHAS_LIVRO = 200;
    const REGISTROS_FOLHA = 3;

    public function __construct(Registro $registro)
    {
        parent::__construct($registro);
        $this->livroRepository = App::make(LivroRepository::class);
    }

    public function paginate($sort = null, $search = null)
    {
        $certificados = $this->model->select('reg_codigo_autenticidade', 'reg_id', 'pes_id', 'pes_nome', 'liv_tipo_livro')
            ->join('acd_livros', 'reg_liv_id', '=', 'liv_id')
            ->join('acd_certificados', 'crt_reg_id', '=', 'reg_id')
            ->join('acd_matriculas', 'crt_mat_id', '=', 'mat_id')
            ->join('acd_alunos', 'mat_alu_id', '=', 'alu_id')
            ->join('gra_pessoas', 'alu_pes_id', '=', 'pes_id')
            ->distinct();

        $diplomados = $this->model->select('reg_codigo_autenticidade', 'reg_id', 'pes_id', 'pes_nome', 'liv_tipo_livro')
            ->join('acd_livros', 'reg_liv_id', '=', 'liv_id')
            ->join('acd_diplomas', 'dip_reg_id', '=', 'reg_id')
            ->join('acd_matriculas', 'dip_mat_id', '=', 'mat_id')
            ->join('acd_alunos', 'mat_alu_id', '=', 'alu_id')
            ->join('gra_pessoas', 'alu_pes_id', '=', 'pes_id')
            ->distinct();

        if (!empty($search)) {
            foreach ($search as $key => $value) {
                switch ($value['type']) {
                    case 'like':
                        $diplomados = $diplomados->where($value['field'], $value['type'], "%{$value['term']}%");
                        $certificados = $certificados->where($value['field'], $value['type'], "%{$value['term']}%");
                        break;
                    default:
                        $diplomados = $diplomados->where($value['field'], $value['type'], $value['term']);
                        $certificados = $certificados->where($value['field'], $value['type'], $value['term']);
                }
            }
        }

        $result = $certificados->union($diplomados);

        if (!empty($sort)) {
            $result = $result->orderBy($sort['field'], $sort['sort']);
        }

        return $this->model->paginateUnion($result->get(), 15);
    }

    public function create(array $data)
    {
        try {
            if (isset($data['tipo_livro']) && $data['tipo_livro'] == 'CERTIFICADO') {
                return $this->certificar($data);
            }

            return $this->diplomar($data);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                throw $e;
            }
        }
    }

    private function certificar(array $data)
    {
        $ultimoLivro = $this->ultimoLivro();

        // Se null, nao ha livros de certificacao ainda ou se nao ha vagas no livro, criar um novo
        if (!$ultimoLivro || !$this->livroTemRegistroDisponiveis($ultimoLivro)) {
            $numero = 1;

            if (!is_null($ultimoLivro)) {
                $numero = $ultimoLivro->liv_numero;
                $numero++;
            }

            $this->livroRepository->create([
                'liv_numero' => $numero,
                'liv_tipo_livro' => 'CERTIFICADO'
            ]);

            $ultimoLivro = $this->ultimoLivro();
        }

        // Adicionar ao livro
        $uuid = Uuid::uuid4();

        $registro = parent::create([
            'reg_liv_id' => $ultimoLivro->liv_id,
            'reg_usr_id' => Auth::user()->usr_id,
            'reg_folha' => $this->folhaParaNovoRegistro($ultimoLivro),
            'reg_registro' => $this->numeroParaNovoRegistro($ultimoLivro),
            'reg_codigo_autenticidade' => $uuid->toString()
        ]);

        $certificado = new Certificado();
        $certificado->crt_reg_id = $registro->reg_id;
        $certificado->crt_mat_id = $data['matricula'];
        $certificado->crt_mdo_id = $data['modulo'];
        $certificado->save();

        return $registro;
    }

    private function diplomar(array $data)
    {
        $ultimoLivro = $this->ultimoLivro('DIPLOMA');

        // Se null, nao ha livros de diploma ainda ou se nao ha vagas no livro, criar um novo
        if (!$ultimoLivro || !$this->livroTemRegistroDisponiveis($ultimoLivro)) {
            $numero = 1;

            if (!is_null($ultimoLivro)) {
                $numero = $ultimoLivro->liv_numero;
                $numero++;
            }

            $this->livroRepository->create([
                'liv_numero' => $numero,
                'liv_tipo_livro' => 'DIPLOMA'
            ]);

            $ultimoLivro = $this->ultimoLivro('DIPLOMA');
        }

        // Adicionar ao livro
        $uuid = Uuid::uuid4();

        $registro = parent::create([
            'reg_liv_id' => $ultimoLivro->liv_id,
            'reg_usr_id' => Auth::user()->usr_id,
            'reg_folha' => $this->folhaParaNovoRegistro($ultimoLivro),
            'reg_registro' => $this->numeroParaNovoRegistro($ultimoLivro),
            'reg_codigo_autenticidade' => $uuid->toString()
        ]);

        // Diploma
        $diploma = new Diploma();
        $diploma->dip_reg_id = $registro->reg_id;
        $diploma->dip_mat_id = $data['matricula'];
        $diploma->save();

        return $registro;
    }

    /**
     * @param string $tipo CERTIFICADO || DIPLOMA
     * @return mixed
     */
    private function ultimoLivro($tipo = 'CERTIFICADO')
    {
        $result = $this->livroRepository->findBy([
            'liv_tipo_livro' => $tipo
        ]);

        return $result->last();
    }

    private function livroTemRegistroDisponiveis(Livro $livro)
    {
        $registros = $this->findBy([
            'reg_liv_id' => $livro->liv_id
        ]);

        return !($registros->count() == self::FOLHAS_LIVRO * self::REGISTROS_FOLHA);
    }

    private function folhaParaNovoRegistro(Livro $livro)
    {
        $registrosDisponiveis = $this->livroTemRegistroDisponiveis($livro);
        $ultimoRegistro = $this->ultimoRegistroLivro($livro);

        if ($registrosDisponiveis && $ultimoRegistro) {
            // Pula para proxima folha
            if ($ultimoRegistro->reg_registro >= self::REGISTROS_FOLHA) {
                return (int)($ultimoRegistro->reg_folha + 1);
            }

            // O novo registro fica na mesma folha
            return (int)($ultimoRegistro->reg_folha);
        }

        // Livro nao possui registros disponiveis ou ainda nao tem registros
        return 1;
    }

    private function numeroParaNovoRegistro(Livro $livro)
    {
        $registrosDisponiveis = $this->livroTemRegistroDisponiveis($livro);
        $ultimoRegistro = $this->ultimoRegistroLivro($livro);

        if ($registrosDisponiveis && $ultimoRegistro) {
            // Pula para proxima folha, iniciando  contagem de registros da folha
            if ($ultimoRegistro->reg_registro >= self::REGISTROS_FOLHA) {
                return 1;
            }

            // Incrementa
            return (int)($ultimoRegistro->reg_registro + 1);
        }


        // Livro nao possui registros disponiveis ou ainda nao tem registros
        return 1;
    }

    private function ultimoRegistroLivro(Livro $livro)
    {
        $registros = $this->findBy([
            'reg_liv_id' => $livro->liv_id
        ]);

        return $registros->last();
    }


    /**
     * @param $matriculaId
     * @param $moduloId
     * @return bool
     */
    public function matriculaTemRegistro($matriculaId, $moduloId): bool
    {
        $result = $this->model
            ->join('acd_certificados', 'reg_id', '=', 'crt_reg_id')
            ->where('crt_mat_id', '=', $matriculaId)
            ->where('crt_mdo_id', '=', $moduloId)
            ->get();

        return (bool)$result->count();
    }

    /**
     * @param $options
     * @return mixed
     */
    public function findBy(array $options = [])
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

    public function detalhesDoRegistro($id)
    {
        $registro = Registro::find($id);

        $query = $this->model
            ->select('acd_registros.*', 'pes_nome', 'pes_email', 'crs_nome', 'liv_tipo_livro')
            ->join('acd_livros', 'reg_liv_id', '=', 'liv_id');


        if ($registro->livro->liv_tipo_livro == "CERTIFICADO") {
            $query = $query
                ->join('acd_certificados', 'crt_reg_id', '=', 'reg_id')
                ->join('acd_matriculas', 'crt_mat_id', '=', 'mat_id');
        }

        if ($registro->livro->liv_tipo_livro == "DIPLOMA") {
            $query = $query
                ->join('acd_diplomas', 'dip_reg_id', '=', 'reg_id')
                ->join('acd_matriculas', 'dip_mat_id', '=', 'mat_id')
                ->addSelect('dip_processo');
        }

        return $query
            ->join('acd_turmas', 'mat_trm_id', '=', 'trm_id')
            ->join('acd_ofertas_cursos', 'trm_ofc_id', '=', 'ofc_id')
            ->join('acd_cursos', 'ofc_crs_id', '=', 'crs_id')
            ->join('acd_alunos', 'mat_alu_id', '=', 'alu_id')
            ->join('gra_pessoas', 'alu_pes_id', '=', 'pes_id')
            ->where('reg_id', '=', $id)->get()->first();
    }
}
