<?php

use Tests\ModulosTestCase;
use Modulos\Academico\Models\ListaSemtur;
use Illuminate\Database\Eloquent\Collection;
use Modulos\Academico\Repositories\ListaSemturRepository;

class ListaSemturRepositoryTest extends ModulosTestCase
{
    protected $repo;

    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(ListaSemturRepository::class);
    }

    public function testAllWithEmptyDatabase()
    {
        $response = $this->repo->all();

        $this->assertInstanceOf(Collection::class, $response);
        $this->assertEquals(0, $response->count());
    }

    public function testValidateMatricula()
    {
        $matricula = factory(\Modulos\Academico\Models\Matricula::class)->create();

        $response = $this->repo->validateMatricula($matricula);

        $this->assertEquals(false, $response);
    }

    public function testValidateMatriculaWithRg()
    {
        $matricula = factory(\Modulos\Academico\Models\Matricula::class)->create();
        factory(\Modulos\Geral\Models\Documento::class)->create(['doc_pes_id' => $matricula->aluno->alu_pes_id, 'doc_tpd_id' => 1]);

        $response = $this->repo->validateMatricula($matricula);

        $this->assertEquals(false, $response);
    }

    public function testValidateMatriculaWithRgAndCpf()
    {
        $matricula = factory(\Modulos\Academico\Models\Matricula::class)->create();
        factory(\Modulos\Geral\Models\Documento::class)->create(['doc_pes_id' => $matricula->aluno->alu_pes_id, 'doc_tpd_id' => 1]);
        factory(\Modulos\Geral\Models\Documento::class)->create(['doc_pes_id' => $matricula->aluno->alu_pes_id, 'doc_tpd_id' => 2]);

        $response = $this->repo->validateMatricula($matricula);

        $this->assertEquals(true, $response);
    }

    public function testValidateMatriculaWithoutCidade()
    {
        $pessoa = factory(\Modulos\Geral\Models\Pessoa::class)->create(['pes_cidade' => '']);
        $aluno = factory(\Modulos\Academico\Models\Aluno::class)->create(['alu_pes_id' => $pessoa->pes_id]);
        $matricula = factory(\Modulos\Academico\Models\Matricula::class)->create(['mat_alu_id' => $aluno->alu_id]);

        $response = $this->repo->validateMatricula($matricula);
        $this->assertEquals(false, $response);
    }

    public function testValidateMatriculaWithoutMae()
    {
        $pessoa = factory(\Modulos\Geral\Models\Pessoa::class)->create(['pes_mae' => '']);
        $aluno = factory(\Modulos\Academico\Models\Aluno::class)->create(['alu_pes_id' => $pessoa->pes_id]);
        $matricula = factory(\Modulos\Academico\Models\Matricula::class)->create(['mat_alu_id' => $aluno->alu_id]);

        $response = $this->repo->validateMatricula($matricula);
        $this->assertEquals(false, $response);
    }


    public function testPaginateRequest()
    {
        $lista = factory(ListaSemtur::class)->create();
        $matriculas = factory(\Modulos\Academico\Models\Matricula::class, 10)->create();

        foreach ($matriculas as $matricula) {
            $lista->matriculas()->attach($matricula->mat_id);
        }

        $response = $this->repo->paginateRequest();

        $this->assertGreaterThan(0, $response->total());
    }

    public function testPaginateRequestWithSearch()
    {
        $lista = factory(ListaSemtur::class)->create();
        $matriculas = factory(\Modulos\Academico\Models\Matricula::class, 10)->create();

        foreach ($matriculas as $matricula) {
            $lista->matriculas()->attach($matricula->mat_id);
        }

        $response = $this->repo->paginateRequest(array("lst_id" => $lista->lst_id, "lst_nome" => $lista->lst_nome, "field" => "lst_nome", "sort" => "asc"));

        $this->assertGreaterThan(0, $response->total());
    }

    public function testGetTurmasByLista()
    {
        $lista = factory(ListaSemtur::class)->create();
        $matriculas = factory(\Modulos\Academico\Models\Matricula::class, 10)->create();

        foreach ($matriculas as $matricula) {
            $lista->matriculas()->attach($matricula->mat_id);
        }

        $response = $this->repo->getTurmasByLista($lista->lst_id);

        $this->assertEquals(10, count($response));
    }

    public function testGetPolosByLista()
    {
        $lista = factory(ListaSemtur::class)->create();
        $matriculas = factory(\Modulos\Academico\Models\Matricula::class, 10)->create();

        foreach ($matriculas as $matricula) {
            $lista->matriculas()->attach($matricula->mat_id);
        }

        $response = $this->repo->getPolosByLista($lista->lst_id);

        $this->assertEquals(10, count($response));
    }

    public function testFindAll()
    {
        $lista = factory(ListaSemtur::class)->create();
        $matricula = factory(\Modulos\Academico\Models\Matricula::class)->create();

        $lista->matriculas()->attach($matricula->mat_id);

        $response = $this->repo->findAll(['pes_nome' => $matricula->aluno->pessoa->pes_nome], ['pes_nome' => 'asc'], ['mat_id', 'pes_nome', 'trm_nome', 'pol_nome']);

        $this->assertEquals(1, count($response));
    }

    public function testFindAllReturnEmpty()
    {
        $lista = factory(ListaSemtur::class)->create();
        $matricula = factory(\Modulos\Academico\Models\Matricula::class)->create();

        $lista->matriculas()->attach($matricula->mat_id);

        $response = $this->repo->findAll([]);

        $this->assertEmpty($response);
    }

    public function testGetMatriculasOutOfLista()
    {
        $lista = factory(ListaSemtur::class)->create();
        $matricula = factory(\Modulos\Academico\Models\Matricula::class)->create();
        $response = $this->repo->getMatriculasOutOfLista($lista->lst_id, $matricula->mat_trm_id, $matricula->mat_pol_id);

        $this->assertNotEmpty($response);
    }
}
