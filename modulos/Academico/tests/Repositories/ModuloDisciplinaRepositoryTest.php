<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modulos\Academico\Repositories\ModuloDisciplinaRepository;
use Modulos\Academico\Models\ModuloDisciplina;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

use Modulos\Academico\Models\Curso;
use Modulos\Geral\Repositories\DocumentoRepository;
use Uemanet\EloquentTable\TableCollection;
use Tests\ModulosTestCase;

class ModuloDisciplinaRepositoryTest extends ModulosTestCase
{
    use DatabaseTransactions,
        WithoutMiddleware;

    protected $repo;

    protected $docrepo;

    public function setUp(): void
    {
        parent::setUp();

        $this->repo = $this->app->make(ModuloDisciplinaRepository::class);
        $this->docrepo = $this->app->make(DocumentoRepository::class);
        $this->table = 'acd_modulos_disciplinas';
    }

    public function testCreate()
    {
        $curso = factory(Modulos\Academico\Models\Curso::class)->create();

        $matrizCurricular = factory(Modulos\Academico\Models\MatrizCurricular::class)->create([
            'mtc_crs_id' => $curso->crs_id
        ]);

        $moduloMatriz = factory(Modulos\Academico\Models\ModuloMatriz::class)->create([
            'mdo_mtc_id' => $matrizCurricular->mtc_id
        ]);

        $disciplina = factory(Modulos\Academico\Models\Disciplina::class)->create([
            'dis_nvc_id' => $curso->crs_nvc_id,
            'dis_nome' => 'Disciplina 1'
        ]);

        factory(ModuloDisciplina::class)->create([
            'mdc_dis_id' => $disciplina->dis_id,
            'mdc_mdo_id' => $moduloMatriz->mdo_id,
            'mdc_tipo_disciplina' => 'obrigatoria'
        ]);

        $response = factory(ModuloDisciplina::class)->create([
            'mdc_dis_id' => $disciplina->dis_id,
            'mdc_mdo_id' => $moduloMatriz->mdo_id,
            'mdc_tipo_disciplina' => 'obrigatoria'
        ]);

        $data = $response->toArray();

        $this->assertInstanceOf(ModuloDisciplina::class, $response);

        $this->assertArrayHasKey('mdc_id', $data);
    }

    public function testFind()
    {
        $dados = factory(ModuloDisciplina::class)->create();
        // Recupera id do curso a partir do Factory
        // Um Accessor é usado no model para retornar o nome do curso em vez de seu id
        $data = $dados->first()->toArray();
        $values = [
            'Obrigatória' => 'obrigatoria',
            'Optativa' => 'optativa',
            'Eletiva' => 'eletiva',
            'TCC' => 'tcc'
        ];
        $data['mdc_tipo_disciplina'] = $values[$data['mdc_tipo_disciplina']];

        $this->assertDatabaseHas('acd_modulos_disciplinas', $data);
    }

    public function testRepositoryCreateDisciplinaExists()
    {
        $curso = factory(Modulos\Academico\Models\Curso::class)->create();

        $matrizCurricular = factory(Modulos\Academico\Models\MatrizCurricular::class)->create([
            'mtc_crs_id' => $curso->crs_id
        ]);

        $moduloMatriz = factory(Modulos\Academico\Models\ModuloMatriz::class)->create([
            'mdo_mtc_id' => $matrizCurricular->mtc_id
        ]);

        $disciplina = factory(Modulos\Academico\Models\Disciplina::class)->create([
            'dis_nvc_id' => $curso->crs_nvc_id,
            'dis_nome' => 'Disciplina 1'
        ]);

        factory(ModuloDisciplina::class)->create([
            'mdc_dis_id' => $disciplina->dis_id,
            'mdc_mdo_id' => $moduloMatriz->mdo_id,
            'mdc_tipo_disciplina' => 'obrigatoria'
        ]);

        $arraydata = [
            'dis_id' => $disciplina->dis_id,
            'tipo_disciplina' => 'obrigatoria',
            'mtc_id' => $moduloMatriz->matriz->mtc_id,
            'mod_id' => $moduloMatriz->mdo_id
        ];

        $response = $this->repo->create($arraydata);

        $this->assertEquals($response['type'], 'error');
    }

    public function testRepositoryCreateDisciplinaExistsName()
    {
        $curso = factory(Modulos\Academico\Models\Curso::class)->create();

        $matrizCurricular = factory(Modulos\Academico\Models\MatrizCurricular::class)->create([
            'mtc_crs_id' => $curso->crs_id
        ]);

        $moduloMatriz = factory(Modulos\Academico\Models\ModuloMatriz::class)->create([
            'mdo_mtc_id' => $matrizCurricular->mtc_id
        ]);

        $disciplina1 = factory(Modulos\Academico\Models\Disciplina::class)->create([
            'dis_nvc_id' => $curso->crs_nvc_id,
            'dis_nome' => 'Disciplina 1'
        ]);

        $disciplina2 = factory(Modulos\Academico\Models\Disciplina::class)->create([
            'dis_nvc_id' => $curso->crs_nvc_id,
            'dis_nome' => 'Disciplina 1'
        ]);

        factory(ModuloDisciplina::class)->create([
            'mdc_dis_id' => $disciplina1->dis_id,
            'mdc_mdo_id' => $moduloMatriz->mdo_id,
            'mdc_tipo_disciplina' => 'obrigatoria'
        ]);

        $arraydata = [
            'dis_id' => $disciplina2->dis_id,
            'tipo_disciplina' => 'obrigatoria',
            'mtc_id' => $moduloMatriz->matriz->mtc_id,
            'mod_id' => $moduloMatriz->mdo_id
        ];

        $response = $this->repo->create($arraydata);

        $this->assertEquals($response['type'], 'error');
    }

    public function testRepositoryCreateDisciplinaExistsTcc()
    {
        $curso = factory(Modulos\Academico\Models\Curso::class)->create();

        $matrizCurricular = factory(Modulos\Academico\Models\MatrizCurricular::class)->create([
            'mtc_crs_id' => $curso->crs_id
        ]);

        $moduloMatriz = factory(Modulos\Academico\Models\ModuloMatriz::class)->create([
            'mdo_mtc_id' => $matrizCurricular->mtc_id
        ]);

        $disciplina1 = factory(Modulos\Academico\Models\Disciplina::class)->create([
            'dis_nvc_id' => $curso->crs_nvc_id,
            'dis_nome' => 'Disciplina 1'
        ]);

        $disciplina2 = factory(Modulos\Academico\Models\Disciplina::class)->create([
            'dis_nvc_id' => $curso->crs_nvc_id,
            'dis_nome' => 'Disciplina 2'
        ]);

        factory(ModuloDisciplina::class)->create([
            'mdc_dis_id' => $disciplina1->dis_id,
            'mdc_mdo_id' => $moduloMatriz->mdo_id,
            'mdc_tipo_disciplina' => 'tcc'
        ]);

        $arraydata = [
            'dis_id' => $disciplina2->dis_id,
            'tipo_disciplina' => 'tcc',
            'mtc_id' => $moduloMatriz->matriz->mtc_id,
            'mod_id' => $moduloMatriz->mdo_id
        ];

        $response = $this->repo->create($arraydata);

        $this->assertEquals($response['type'], 'error');
    }

    public function testRepositoryCreateDisciplinaExistsPreRequisitos()
    {
        $curso = factory(Modulos\Academico\Models\Curso::class)->create();

        $matrizCurricular = factory(Modulos\Academico\Models\MatrizCurricular::class)->create([
            'mtc_crs_id' => $curso->crs_id
        ]);

        $moduloMatriz1 = factory(Modulos\Academico\Models\ModuloMatriz::class)->create([
            'mdo_mtc_id' => $matrizCurricular->mtc_id
        ]);

        $moduloMatriz2 = factory(Modulos\Academico\Models\ModuloMatriz::class)->create([
            'mdo_mtc_id' => $matrizCurricular->mtc_id
        ]);

        $disciplina1 = factory(Modulos\Academico\Models\Disciplina::class)->create([
            'dis_nvc_id' => $curso->crs_nvc_id,
            'dis_nome' => 'Disciplina 1'
        ]);

        $disciplina2 = factory(Modulos\Academico\Models\Disciplina::class)->create([
            'dis_nvc_id' => $curso->crs_nvc_id,
            'dis_nome' => 'Disciplina 2'
        ]);

        $moduloDisciplina1 = factory(ModuloDisciplina::class)->create([
            'mdc_dis_id' => $disciplina1->dis_id,
            'mdc_mdo_id' => $moduloMatriz1->mdo_id,
            'mdc_tipo_disciplina' => 'obrigatoria'
        ]);

        $arraydata = [
            'dis_id' => $disciplina2->dis_id,
            'tipo_disciplina' => 'obrigatoria',
            'mtc_id' => $moduloMatriz2->matriz->mtc_id,
            'mod_id' => $moduloMatriz2->mdo_id,
            'pre_requisitos' => [$moduloDisciplina1->mdc_id]
        ];

        $response = $this->repo->create($arraydata);

        $this->assertEquals($response['type'], 'success');
    }

    public function testUpdateExistsDisciplinaTcc()
    {
        $data = $this->mockData();

        list(, $moduloMatriz, $moduloDisciplina) = $data;

        $disciplina = factory(Modulos\Academico\Models\Disciplina::class)->create([
            'dis_nvc_id' => 1
        ]);

        $moduloDis = factory(Modulos\Academico\Models\ModuloDisciplina::class)->create([
            'mdc_dis_id' => $disciplina->dis_id,
            'mdc_mdo_id' => $moduloMatriz->mdo_id,
            'mdc_tipo_disciplina' => 'eletiva',
        ]);

        $updateArray = $moduloDis->toArray();
        $updateArray['mdc_tipo_disciplina'] = 'tcc';

        $modulodisciplinaId = $updateArray['mdc_id'];
        unset($updateArray['mdc_id']);

        $response = $this->repo->update($updateArray, $modulodisciplinaId);

        $this->assertEquals($response['type'], 'error');
    }

    public function testUpdateDisciplinaTcc()
    {
        $data = $this->mockData();

        list(, , $moduloDisciplina) = $data;

        $updateArray = $moduloDisciplina->toArray();
        $updateArray['mdc_tipo_disciplina'] = 'tcc';

        $modulodisciplinaId = $updateArray['mdc_id'];
        unset($updateArray['mdc_id']);

        $response = $this->repo->update($updateArray, $modulodisciplinaId);

        $this->assertEquals(1, $response);
    }

    public function testUpdatePreRequisito()
    {
        $data = $this->mockData();

        list(, $moduloMtc, $moduloDisciplina) = $data;

        $moduloMatriz = factory(Modulos\Academico\Models\ModuloMatriz::class)->create([
            'mdo_mtc_id' => $moduloMtc->mdo_mtc_id,
        ]);

        $disciplina = factory(Modulos\Academico\Models\Disciplina::class)->create();

        $moduloDis = factory(Modulos\Academico\Models\ModuloDisciplina::class)->create([
            'mdc_dis_id' => $disciplina->dis_id,
            'mdc_mdo_id' => $moduloMatriz->mdo_id,
            'mdc_tipo_disciplina' => 'eletiva',
        ]);

        $updateArray = $moduloDis->toArray();
        $updateArray['mdc_pre_requisitos'][] = $moduloDisciplina->mdc_id;
        $updateArray['mdc_tipo_disciplina'] = $moduloDis->getRawOriginal('mdc_tipo_disciplina');

        $modulodisciplinaId = $updateArray['mdc_id'];
        unset($updateArray['mdc_id']);

        $response = $this->repo->update($updateArray, $modulodisciplinaId);

        $this->assertEquals(1, $response);
    }

    public function testUpdateNoAptasPreRequisito()
    {
        $data = $this->mockData();

        list(, $moduloMatriz, $moduloDisciplina) = $data;

        $disciplina = factory(Modulos\Academico\Models\Disciplina::class)->create();

        $moduloDis = factory(Modulos\Academico\Models\ModuloDisciplina::class)->create([
            'mdc_dis_id' => $disciplina->dis_id,
            'mdc_mdo_id' => $moduloMatriz->mdo_id,
            'mdc_tipo_disciplina' => 'eletiva',
        ]);

        $updateArray = $moduloDis->toArray();
        $updateArray['mdc_pre_requisitos'][] = $moduloDisciplina->mdc_id;

        $modulodisciplinaId = $updateArray['mdc_id'];
        unset($updateArray['mdc_id']);

        $response = $this->repo->update($updateArray, $modulodisciplinaId);

        $this->assertEquals($response['type'], 'error');
    }

    public function testDelete()
    {
        $data = factory(ModuloDisciplina::class)->create();
        $modulodisciplinaId = $data->mdc_id;

        $response = $this->repo->delete($modulodisciplinaId);

        $this->assertEquals(1, $response);

        $response = $this->repo->delete(random_int(100, 150));

        $this->assertFalse($response);
    }

    public function testDeleteWithPreRequisitos()
    {
        $curso = factory(Modulos\Academico\Models\Curso::class)->create();

        $matrizCurricular = factory(Modulos\Academico\Models\MatrizCurricular::class)->create([
            'mtc_crs_id' => $curso->crs_id
        ]);

        $moduloMatriz1 = factory(Modulos\Academico\Models\ModuloMatriz::class)->create([
            'mdo_mtc_id' => $matrizCurricular->mtc_id
        ]);

        $moduloMatriz2 = factory(Modulos\Academico\Models\ModuloMatriz::class)->create([
            'mdo_mtc_id' => $matrizCurricular->mtc_id
        ]);

        $disciplina1 = factory(Modulos\Academico\Models\Disciplina::class)->create([
            'dis_nvc_id' => $curso->crs_nvc_id,
            'dis_nome' => 'Disciplina 1'
        ]);

        $disciplina2 = factory(Modulos\Academico\Models\Disciplina::class)->create([
            'dis_nvc_id' => $curso->crs_nvc_id,
            'dis_nome' => 'Disciplina 2'
        ]);

        $moduloDisciplina1 = factory(ModuloDisciplina::class)->create([
            'mdc_dis_id' => $disciplina1->dis_id,
            'mdc_mdo_id' => $moduloMatriz1->mdo_id,
            'mdc_tipo_disciplina' => 'obrigatoria'
        ]);

        $arraydata = [
            'dis_id' => $disciplina2->dis_id,
            'tipo_disciplina' => 'obrigatoria',
            'mtc_id' => $moduloMatriz2->matriz->mtc_id,
            'mod_id' => $moduloMatriz2->mdo_id,
            'pre_requisitos' => [$moduloDisciplina1->mdc_id]
        ];

        $response = $this->repo->create($arraydata);
        $this->assertEquals('success', $response['type']);

        $beforeDelete = json_decode(ModuloDisciplina::all()->last()->mdc_pre_requisitos, true);

        $this->repo->delete($moduloDisciplina1->mdc_id);

        $afterDelete = json_decode(ModuloDisciplina::all()->last()->mdc_pre_requisitos, true);

        $this->assertNotEquals($beforeDelete, $afterDelete);
    }

    public function testDeleteException()
    {
        $curso = factory(Modulos\Academico\Models\Curso::class)->create();

        $matrizCurricular = factory(Modulos\Academico\Models\MatrizCurricular::class)->create([
            'mtc_crs_id' => $curso->crs_id
        ]);

        $moduloMatriz1 = factory(Modulos\Academico\Models\ModuloMatriz::class)->create([
            'mdo_mtc_id' => $matrizCurricular->mtc_id
        ]);

        $moduloMatriz2 = factory(Modulos\Academico\Models\ModuloMatriz::class)->create([
            'mdo_mtc_id' => $matrizCurricular->mtc_id
        ]);

        $disciplina1 = factory(Modulos\Academico\Models\Disciplina::class)->create([
            'dis_nvc_id' => $curso->crs_nvc_id,
            'dis_nome' => 'Disciplina 1'
        ]);

        $disciplina2 = factory(Modulos\Academico\Models\Disciplina::class)->create([
            'dis_nvc_id' => $curso->crs_nvc_id,
            'dis_nome' => 'Disciplina 2'
        ]);

        $moduloDisciplina1 = factory(ModuloDisciplina::class)->create([
            'mdc_dis_id' => $disciplina1->dis_id,
            'mdc_mdo_id' => $moduloMatriz1->mdo_id,
            'mdc_tipo_disciplina' => 'obrigatoria'
        ]);

        $arraydata = [
            'mdc_dis_id' => $disciplina2->dis_id,
            'mdc_mdo_id' => $moduloMatriz2->mdo_id,
            'mdc_tipo_disciplina' => 'obrigatoria',
            'mdc_pre_requisitos' => '{json, mal, formado}'
        ];

        factory(ModuloDisciplina::class)->create($arraydata);

        $this->repo->delete($moduloDisciplina1->mdc_id);
        $afterDelete = json_decode(ModuloDisciplina::all()->last()->mdc_pre_requisitos, true);

        $this->assertNull($afterDelete);
    }

    public function testLists()
    {
        $entries = factory(ModuloDisciplina::class, 2)->create();

        $model = new ModuloDisciplina();
        $expected = $model->pluck('mdc_tipo_disciplina', 'mdc_id');
        $fromRepository = $this->repo->lists('mdc_id', 'mdc_tipo_disciplina');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        factory(ModuloDisciplina::class)->create([
            'mdc_tipo_disciplina' => 'eletiva'
        ]);

        $searchResult = $this->repo->search(array(['mdc_tipo_disciplina', '=', 'eletiva']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        $entry = factory(ModuloDisciplina::class)->create([
            'mdc_tipo_disciplina' => "eletiva"
        ]);

        $expected = [
            'mdc_id' => $entry->mdc_id,
            'mdc_tipo_disciplina' => $entry->mdc_tipo_disciplina
        ];

        $searchResult = $this->repo->search(array(['mdc_tipo_disciplina', '=', "eletiva"]), ['mdc_id', 'mdc_tipo_disciplina']);

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
        $this->assertEquals($expected, $searchResult->first()->toArray());
    }

    public function testAll()
    {
        // With empty database
        $collection = $this->repo->all();

        $this->assertEquals(0, $collection->count());

        // Non-empty database
        $created = factory(ModuloDisciplina::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(ModuloDisciplina::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new ModuloDisciplina();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(ModuloDisciplina::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(ModuloDisciplina::class, 2)->create();

        $sort = [
            'field' => 'mdc_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->mdc_id);
    }

    public function testPaginateWithSearch()
    {
        $this->mockData();
        factory(ModuloDisciplina::class, 2)->create();
        factory(ModuloDisciplina::class)->create([
            'mdc_tipo_disciplina' => 'eletiva',
        ]);

        $search = [
            [
                'field' => 'mdc_tipo_disciplina',
                'type' => '=',
                'term' => 'eletiva'
            ]
        ];

        $response = $this->repo->paginate(null, $search);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('Eletiva', $response->first()->mdc_tipo_disciplina);
    }

    public function testPaginateWithSortAndSearch()
    {
        $this->mockData();
        factory(ModuloDisciplina::class, 2)->create();
        factory(ModuloDisciplina::class)->create([
            'mdc_tipo_disciplina' => 'eletiva',
        ]);

        $sort = [
            'field' => 'mdc_id',
            'sort' => 'desc'
        ];

        $search = [
            [
                'field' => 'mdc_tipo_disciplina',
                'type' => 'like',
                'term' => 'eletiva'
            ]
        ];

        $response = $this->repo->paginate($sort, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);

        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('Eletiva', $response->first()->mdc_tipo_disciplina);
    }

    public function testPaginateRequest()
    {
        factory(ModuloDisciplina::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'mdc_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }

    public function testVerifyDisciplinaModulo()
    {
        $data = $this->mockData();

        list($disciplina, $modulo) = $data;

        $result = $this->repo->verifyDisciplinaModulo($disciplina->dis_id, $modulo->mdo_id);

        $this->assertTrue($result);
    }

    public function testVerifyDisciplinaModuloFalse()
    {
        $data = $this->mockData();

        $disciplina = factory(Modulos\Academico\Models\Disciplina::class)->create();

        list(, $modulo) = $data;

        $result = $this->repo->verifyDisciplinaModulo($disciplina->dis_id, $modulo->mdo_id);

        $this->assertFalse($result);
    }

    public function testGetAllDisciplinasByModulo()
    {
        $data = $this->mockData();

        list(, $modulo) = $data;

        $result = $this->repo->getAllDisciplinasByModulo($modulo->mdo_id);

        $this->assertNotEmpty($result);
    }

    public function testGetAllDisciplinasByModuloPreRequisitos()
    {
        $data = $this->mockData();

        list(, $modulo, $moduloDisciplina) = $data;

        $moduloMatriz = factory(Modulos\Academico\Models\ModuloMatriz::class)->create([
            'mdo_mtc_id' => $modulo->mdo_mtc_id,
        ]);

        $disciplina = factory(Modulos\Academico\Models\Disciplina::class)->create();

        factory(Modulos\Academico\Models\ModuloDisciplina::class)->create([
            'mdc_dis_id' => $disciplina->dis_id,
            'mdc_mdo_id' => $moduloMatriz->mdo_id,
            'mdc_tipo_disciplina' => 'eletiva',
            'mdc_pre_requisitos' => json_encode(array($moduloDisciplina->mdc_id))
        ]);

        $result = $this->repo->getAllDisciplinasByModulo($moduloMatriz->mdo_id);

        $this->assertNotEmpty($result);
    }

    public function testGetAllDisciplinasNotOfertadasByModulo()
    {
        $data = $this->mockData();

        list(, $modulo, $moduloDisciplina) = $data;

        $turmaId = $moduloDisciplina->ofertasDisciplinas->first()->ofd_trm_id;
        $perdId = $moduloDisciplina->ofertasDisciplinas->first()->ofd_per_id;
        $cursoNvcId = $modulo->matriz->curso->first()->crs_nvc_id;


        $disciplina = factory(Modulos\Academico\Models\Disciplina::class)->create([
            'dis_nvc_id' => $cursoNvcId
        ]);

        $moduloDisciplina = factory(Modulos\Academico\Models\ModuloDisciplina::class)->create([
            'mdc_dis_id' => $disciplina->dis_id,
            'mdc_mdo_id' => $modulo->mdo_id,
            'mdc_tipo_disciplina' => 'eletiva',
            'mdc_pre_requisitos' => json_encode(array($moduloDisciplina->mdc_id))
        ]);

        $result = $this->repo->getAllDisciplinasNotOfertadasByModulo($modulo->mdo_id, $turmaId, $perdId);

        $this->assertNotEmpty($result);
    }

    public function testGetDisciplinasPreRequisitos()
    {
        $data = $this->mockData();

        list(, $modulo, $moduloDisciplina) = $data;

        $moduloMatriz = factory(Modulos\Academico\Models\ModuloMatriz::class)->create([
            'mdo_mtc_id' => $modulo->mdo_mtc_id,
        ]);

        $disciplina = factory(Modulos\Academico\Models\Disciplina::class)->create();

        $moduloDisReq = factory(Modulos\Academico\Models\ModuloDisciplina::class)->create([
            'mdc_dis_id' => $disciplina->dis_id,
            'mdc_mdo_id' => $moduloMatriz->mdo_id,
            'mdc_tipo_disciplina' => 'eletiva',
            'mdc_pre_requisitos' => json_encode(array($moduloDisciplina->mdc_id))
        ]);

        $result = $this->repo->getDisciplinasPreRequisitos($moduloDisReq->mdc_id);

        $this->assertNotEmpty($result);
    }


    private function mockData()
    {
        $curso = factory(Modulos\Academico\Models\Curso::class)->create([
            'crs_nvc_id' => 1,
        ]);

        $matrizCurricular = factory(Modulos\Academico\Models\MatrizCurricular::class)->create([
            'mtc_crs_id' => $curso->crs_id
        ]);

        $oferta = factory(Modulos\Academico\Models\OfertaCurso::class)->create([
            'ofc_crs_id' => $curso->crs_id,
            'ofc_mtc_id' => $matrizCurricular->mtc_id,
        ]);

        $turma = factory(Modulos\Academico\Models\Turma::class)->create([
            'trm_ofc_id' => $oferta->ofc_id,
        ]);

        $polo = factory(Modulos\Academico\Models\Polo::class)->create();
        $oferta->polos()->attach($polo->pol_id);

        $grupo = factory(Modulos\Academico\Models\Grupo::class)->create([
            'grp_trm_id' => $turma->trm_id,
            'grp_pol_id' => $polo->pol_id
        ]);

        $moduloMatriz = factory(Modulos\Academico\Models\ModuloMatriz::class)->create([
            'mdo_mtc_id' => $matrizCurricular->mtc_id
        ]);

        $disciplina = factory(Modulos\Academico\Models\Disciplina::class)->create([
            'dis_nvc_id' => $curso->crs_nvc_id
        ]);

        $moduloDisciplina = factory(Modulos\Academico\Models\ModuloDisciplina::class)->create([
            'mdc_dis_id' => $disciplina->dis_id,
            'mdc_mdo_id' => $moduloMatriz->mdo_id,
            'mdc_tipo_disciplina' => 'tcc'
        ]);

        $professor = factory(Modulos\Academico\Models\Professor::class)->create();

        $ofertaDisciplina = factory(Modulos\Academico\Models\OfertaDisciplina::class)->create([
            'ofd_mdc_id' => $moduloDisciplina->mdc_id,
            'ofd_trm_id' => $turma->trm_id,
            'ofd_per_id' => $turma->trm_per_id,
            'ofd_prf_id' => $professor->prf_id,
            'ofd_tipo_avaliacao' => 'numerica',
            'ofd_qtd_vagas' => 500
        ]);

        factory(\Modulos\Geral\Models\Titulacao::class, 7)->create();

        $titulacaoProfessor = factory(Modulos\Geral\Models\TitulacaoInformacao::class)->create([
            'tin_pes_id' => $professor->pessoa->pes_id,
            'tin_tit_id' => random_int(2, 7),
        ]);

        $matricula = factory(\Modulos\Academico\Models\Matricula::class)->create([
            'mat_trm_id' => $turma->trm_id,
            'mat_pol_id' => $polo->pol_id,
            'mat_grp_id' => $grupo->grp_id,
        ]);

        $matriculaOfertaDisciplina = factory(Modulos\Academico\Models\MatriculaOfertaDisciplina::class)->create([
            'mof_mat_id' => $matricula->mat_id,
            'mof_ofd_id' => $ofertaDisciplina->ofd_id,
            'mof_tipo_matricula' => 'matriculacomum',
            'mof_situacao_matricula' => 'aprovado_media'
        ]);

        $rg = $this->docrepo->create(['doc_pes_id' => $matricula->aluno->pessoa->pes_id, 'doc_tpd_id' => 2, 'doc_conteudo' => '123456', 'doc_data_expedicao' => '10/10/2000']);
        $cpf = $this->docrepo->create(['doc_pes_id' => $matricula->aluno->pessoa->pes_id, 'doc_tpd_id' => 1, 'doc_conteudo' => '123456']);

        return [$disciplina, $moduloMatriz, $moduloDisciplina];
    }
}
