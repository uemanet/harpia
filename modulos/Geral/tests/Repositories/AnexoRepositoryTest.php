<?php

use Tests\ModulosTestCase;
use Modulos\Geral\Models\Anexo;
use Illuminate\Http\UploadedFile;
use Stevebauman\EloquentTable\TableCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modulos\Geral\Repositories\AnexoRepository;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AnexoRepositoryTest extends ModulosTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->repo = $this->app->make(AnexoRepository::class);
        $this->table = 'gra_anexos';
    }

    public function tearDown()
    {
        Storage::deleteDirectory('uploads');
        parent::tearDown();
    }

    /**
     * Mock de um upload de arquivo
     * @param string $file
     * @return UploadedFile
     */
    private function mockUploaded($file = 'test.png')
    {
        $stub = base_path() . DIRECTORY_SEPARATOR . 'modulos/Geral/tests/Repositories/stubs/' . $file;
        $name = str_random(8) . '.png';
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $name;

        copy($stub, $path);

        return new UploadedFile($path, $name, 'image/png', filesize($path), null, true);
    }

    private function mockHashDirectories($location)
    {
        return array(substr($location, 0, 2), substr($location, 2, 2));
    }


    public function testCreate()
    {
        $data = factory(Anexo::class)->raw();
        $entry = $this->repo->create($data);

        $this->assertInstanceOf(Anexo::class, $entry);
        $this->assertDatabaseHas($this->table, $entry->toArray());
    }

    public function testFind()
    {
        $entry = factory(Anexo::class)->create();
        $id = $entry->anx_id;
        $fromRepository = $this->repo->find($id);

        $this->assertInstanceOf(Anexo::class, $fromRepository);
        $this->assertDatabaseHas($this->table, $fromRepository->toArray());
        $this->assertEquals($entry->toArray(), $fromRepository->toArray());
    }

    public function testUpdate()
    {
        $entry = factory(Anexo::class)->create();
        $id = $entry->anx_id;

        $data = $entry->toArray();

        $data['anx_nome'] = 'newname';

        $return = $this->repo->update($data, $id);
        $fromRepository = $this->repo->find($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseHas($this->table, $data);
        $this->assertInstanceOf(Anexo::class, $fromRepository);
        $this->assertEquals($data, $fromRepository->toArray());
    }

    public function testDelete()
    {
        $entry = factory(Anexo::class)->create();
        $id = $entry->anx_id;

        $return = $this->repo->delete($id);

        $this->assertEquals(1, $return);
        $this->assertDatabaseMissing($this->table, $entry->toArray());
    }

    public function testLists()
    {
        $entries = factory(Anexo::class, 2)->create();

        $model = new Anexo();
        $expected = $model->pluck('anx_nome', 'anx_id');
        $fromRepository = $this->repo->lists('anx_id', 'anx_nome');

        $this->assertEquals($expected, $fromRepository);
    }

    public function testSearch()
    {
        $entries = factory(Anexo::class, 2)->create();

        factory(Anexo::class)->create([
            'anx_nome' => 'tofind'
        ]);

        $searchResult = $this->repo->search(array(['anx_nome', '=', 'tofind']));

        $this->assertInstanceOf(TableCollection::class, $searchResult);
        $this->assertEquals(1, $searchResult->count());
    }

    public function testSearchWithSelect()
    {
        factory(Anexo::class, 2)->create();

        $entry = factory(Anexo::class)->create([
            'anx_nome' => 'tofind'
        ]);

        $expected = [
            'anx_id' => $entry->anx_id,
            'anx_nome' => $entry->anx_nome
        ];

        $searchResult = $this->repo->search(array(['anx_nome', '=', 'tofind']), ['anx_id', 'anx_nome']);

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
        $created = factory(Anexo::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $collection->count());
    }

    public function testCount()
    {
        $created = factory(Anexo::class, 10)->create();
        $collection = $this->repo->all();

        $this->assertEquals($created->count(), $this->repo->count());
    }

    public function testGetFillableModelFields()
    {
        $model = new Anexo();
        $this->assertEquals($model->getFillable(), $this->repo->getFillableModelFields());
    }

    public function testPaginateWithoutParameters()
    {
        factory(Anexo::class, 2)->create();

        $response = $this->repo->paginate();

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(1, $response->total());
    }

    public function testPaginateWithSort()
    {
        factory(Anexo::class, 2)->create();

        $sort = [
            'field' => 'anx_id',
            'sort' => 'desc'
        ];

        $response = $this->repo->paginate($sort);

        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertEquals(2, $response->first()->anx_id);
    }

    public function testPaginateWithSearch()
    {
        factory(Anexo::class, 2)->create();
        factory(Anexo::class)->create([
            'anx_nome' => 'tofind',
        ]);

        $search = [
            [
                'field' => 'anx_nome',
                'type' => '=',
                'term' => 'tofind'
            ]
        ];

        $response = $this->repo->paginate(null, $search);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
        $this->assertEquals('tofind', $response->first()->anx_nome);
    }

    public function testPaginateRequest()
    {
        factory(Anexo::class, 2)->create();

        $requestParameters = [
            'page' => '1',
            'field' => 'anx_id',
            'sort' => 'asc'
        ];

        $response = $this->repo->paginateRequest($requestParameters);
        $this->assertInstanceOf(LengthAwarePaginator::class, $response);
        $this->assertGreaterThan(0, $response->total());
    }

    public function testSalvarAnexo()
    {
        $prefix = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
        $this->assertEquals(0, $this->repo->all()->count());

        $uploaded = $this->mockUploaded();
        $anexo = $this->repo->salvarAnexo($uploaded);

        $this->assertInstanceOf(Anexo::class, $anexo);
        $this->assertDatabaseHas($this->table, $anexo->toArray());

        list($firstDir, $sndDir) = $this->mockHashDirectories($anexo->anx_localizacao);
        $path = $prefix . 'uploads' . DIRECTORY_SEPARATOR .  $firstDir . DIRECTORY_SEPARATOR . $sndDir . DIRECTORY_SEPARATOR . $anexo->anx_localizacao;

        $this->assertTrue(file_exists($path));
    }

    public function testSalvarAnexoExistente()
    {
        $prefix = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
        $this->assertEquals(0, $this->repo->all()->count());

        $uploaded = $this->mockUploaded();
        $anexo = $this->repo->salvarAnexo($uploaded);

        $this->assertInstanceOf(Anexo::class, $anexo);
        $this->assertDatabaseHas($this->table, $anexo->toArray());

        list($firstDir, $sndDir) = $this->mockHashDirectories($anexo->anx_localizacao);
        $path = $prefix . 'uploads' . DIRECTORY_SEPARATOR .  $firstDir . DIRECTORY_SEPARATOR . $sndDir . DIRECTORY_SEPARATOR . $anexo->anx_localizacao;

        $this->assertTrue(file_exists($path));

        $uploaded = $this->mockUploaded();
        // Tenta salvar novamente o mesmo arquivo - deve retornar um array com mensagem de erro
        $return = $this->repo->salvarAnexo($uploaded);

        $this->assertTrue(is_array($return));
        $this->assertArrayHasKey('type', $return);
        $this->assertArrayHasKey('message', $return);
    }

    public function testRecuperarAnexo()
    {
        // Mock de upload
        $uploaded = $this->mockUploaded();
        $anexo = $this->repo->salvarAnexo($uploaded);

        $id = $anexo->anx_id;

        $this->assertInstanceOf(Anexo::class, $anexo);

        $recuperado = $this->repo->recuperarAnexo($id);
        $this->assertInstanceOf(BinaryFileResponse::class, $recuperado);

        // Anexo inexistente
        $return = $this->repo->recuperarAnexo(random_int(10, 100));
        $this->assertTrue(is_string($return));
    }

    public function testAtualizarAnexo()
    {
        $prefix = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();

        // Mock de upload
        $uploaded = $this->mockUploaded();

        // 1 - Atualizar anexo inexistente retorna um array com mensagem de erro
        $return = $this->repo->atualizarAnexo(random_int(1, 10), $uploaded);
        $this->assertTrue(is_array($return));
        $this->assertArrayHasKey('type', $return);
        $this->assertArrayHasKey('message', $return);

        // 2 - Atualizar anexo existente com novo arquivo
        $anexo = $this->repo->salvarAnexo($uploaded);

        $id = $anexo->anx_id;

        $this->assertInstanceOf(Anexo::class, $anexo);
        list($firstDir, $sndDir) = $this->mockHashDirectories($anexo->anx_localizacao);
        $oldPath = $prefix . 'uploads' . DIRECTORY_SEPARATOR .  $firstDir . DIRECTORY_SEPARATOR . $sndDir . DIRECTORY_SEPARATOR . $anexo->anx_localizacao;

        // Upload de novo arquivo
        $secondUpload = $this->mockUploaded('alternative.png');
        $this->repo->atualizarAnexo($id, $secondUpload);

        $anexoAtualizado = $this->repo->find($id);

        $this->assertDatabaseMissing($this->table, $anexo->toArray());
        $this->assertDatabaseHas($this->table, $anexoAtualizado->toArray());

        list($firstDir, $sndDir) = $this->mockHashDirectories($anexoAtualizado->anx_localizacao);
        $path = $prefix . 'uploads' . DIRECTORY_SEPARATOR .  $firstDir . DIRECTORY_SEPARATOR . $sndDir . DIRECTORY_SEPARATOR . $anexoAtualizado->anx_localizacao;

        // Antigo arquivo deve ser excluido
        $this->assertFalse(file_exists($oldPath));

        // Novo arquivo deve estar presente
        $this->assertTrue(file_exists($path));

        // 3 - Atualizar um anexo com o mesmo arquivo repetido - deve retornar um array com mensagem de erro
        $novoAnexo = factory(Anexo::class)->create();

        $uploaded = $this->mockUploaded('alternative.png');
        $return = $this->repo->atualizarAnexo($novoAnexo->anx_id, $uploaded);

        $this->assertTrue(is_array($return));
        $this->assertArrayHasKey('type', $return);
        $this->assertArrayHasKey('message', $return);
    }

    public function testDeletarAnexo()
    {
        $prefix = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();

        // 1 - Atualizar anexo inexistente retorna um array com mensagem de erro
        $return = $this->repo->deletarAnexo(random_int(1, 10));
        $this->assertTrue(is_array($return));
        $this->assertArrayHasKey('type', $return);
        $this->assertArrayHasKey('message', $return);

        // 2 - Deletar anexo
        $uploaded = $this->mockUploaded();
        $anexo = $this->repo->salvarAnexo($uploaded);

        $id = $anexo->anx_id;

        $this->assertInstanceOf(Anexo::class, $anexo);

        list($firstDir, $sndDir) = $this->mockHashDirectories($anexo->anx_localizacao);
        $path = $prefix . 'uploads' . DIRECTORY_SEPARATOR .  $firstDir . DIRECTORY_SEPARATOR . $sndDir . DIRECTORY_SEPARATOR . $anexo->anx_localizacao;

        $this->repo->deletarAnexo($id);

        // Arquivo deve ser removido
        $this->assertFalse(file_exists($path));

        // Registro na tabela tambem deve ser removido
        $this->assertNull($this->repo->find($id));
        $this->assertDatabaseMissing($this->table, $anexo->toArray());
    }
}
