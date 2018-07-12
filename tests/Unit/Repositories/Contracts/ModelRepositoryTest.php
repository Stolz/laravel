<?php

namespace Tests\Unit\Repositories\Contracts;

use App\Models\User;
use Tests\TestCase;
use Tests\Traits\CreatesUsers;
use Tests\Traits\RefreshDatabase;

/**
 * This class is meant to tests both \App\Repositories\Contracts\{ModelRepository,SoftDeletableModelRepository}.
 * Since the base model is an abstract class it has no repository. Therefore the user model repository contract
 * is used instead since it implements both of the interfaces under test.
 */
class ModelRepositoryTest extends TestCase
{
    use RefreshDatabase, CreatesUsers;

    /**
     * Run before each test.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->repository = $this->userRepository;

        // Create test user
        $this->model = $this->createUser(['name' => 'test']);
    }

    /**
     * Test retrieve all models.
     *
     * @return void
     */
    public function testAll()
    {
        $this->assertEquals(1, $this->repository->all()->count());
        $this->assertEquals(1, $this->repository->includeSoftDeleted()->all()->count());

        $this->repository->delete($this->model);
        $this->assertEquals(0, $this->repository->all()->count());
        $this->assertEquals(1, $this->repository->includeSoftDeleted()->all()->count());

        $this->repository->forceDelete($this->model);
        $this->assertEquals(0, $this->repository->all()->count());
        $this->assertEquals(0, $this->repository->includeSoftDeleted()->all()->count());
    }

    /**
     * Test save a new model.
     *
     * @return void
     */
    public function testCreate()
    {
        $model = with(clone $this->model)->setEmail(str_random());
        $this->assertTrue($this->repository->create($model));
        $this->assertNotEquals($this->model->getId(), $model->getId());
        $this->assertNotNull($this->model->getCreatedAt());
        $this->assertNotEquals($this->model->getCreatedAt(), $model->getCreatedAt());
    }

    /**
     * Test update an existing model.
     *
     * @return void
     */
    public function testUpdate()
    {
        $this->assertNull($this->model->getUpdatedAt());
        $this->assertTrue($this->repository->update($this->model->setName(str_random())));
        $this->assertNotEquals('test', $this->model->getName());
        $this->assertNotNull($this->model->getUpdatedAt());
    }

    /**
     * Test soft delete a model.
     *
     * @return void
     */
    public function testDelete()
    {
        $this->assertTrue($this->repository->delete($this->model));
        $this->assertNotNull($this->repository->includeSoftDeleted()->find($this->model->getId()));
    }

    /**
     * Test force delete a model.
     *
     * @return void
     */
    public function testForceDelete()
    {
        $this->assertTrue($this->repository->forceDelete($this->model));
        $this->assertNull($this->repository->includeSoftDeleted()->find($this->model->getId()));
    }

    /**
     * Test restore a soft deleted model.
     *
     * @return void
     */
    public function testRestore()
    {
        $this->repository->delete($this->model);
        $this->assertTrue($this->repository->restore($this->model));
        $this->assertNull($this->model->getDeletedAt());
    }

    /**
     * Test retrieve a single model by its primary key.
     *
     * @return void
     */
    public function testFind()
    {
        $this->assertNull($this->repository->find(null));
        $this->assertEquals($this->model->getId(), $this->repository->find($this->model->getId())->getId());
        $this->repository->delete($this->model);
        $this->assertNull($this->repository->find($this->model->getId()));
        $this->assertNotNull($this->repository->includeSoftDeleted()->find($this->model->getId()));
    }

    /**
     * Test retrieve a single model by a given unique field.
     *
     * @return void
     */
    public function testFindBy()
    {
        $this->assertNull($this->repository->findBy('name', null));
        $this->assertEquals('test', $this->repository->findBy('name', 'test')->getName());
        $this->repository->delete($this->model);
        $this->assertNull($this->repository->findBy('name', 'test'));
        $this->assertNotNull($this->repository->includeSoftDeleted()->findBy('name', 'test'));
    }

    /**
     * Test retrieve multiple models by the values of a given field.
     *
     * @return void
     */
    public function testGetBy()
    {
        $this->assertEquals(0, $this->repository->getBy('name', 'xxx')->count());

        $users = $this->repository->getBy('name', 'test');
        $this->assertEquals(1, $users->count());
        $this->assertEquals('test', $users->first()->getName());

        $this->createUser(['name' => 'foo']);
        $users = $this->repository->getBy('name', 'foo');
        $this->assertEquals(1, $users->count());
        $this->assertEquals('foo', $users->first()->getName());

        $users = $this->repository->getBy('name', ['test', 'foo']);
        $this->assertEquals(2, $users->count());
    }

    /**
     * Test retrieve a page of a paginated result of all models.
     *
     * @return void
     */
    public function testPaginate()
    {
        $first = $this->createUser(['name' => 'first']);
        $second = $this->createUser(['name' => 'second']);

        $paginator = $this->repository->paginate($perPage = 1, $page = 1, $sortBy = 'name', $sortDirection = 'asc');
        $this->assertEquals(3, $paginator->total());
        $this->assertEquals($page, $paginator->currentPage());
        $this->assertEquals('first', $paginator->first()->getName());

        $paginator = $this->repository->paginate($perPage = 1, $page = 2, $sortBy = 'name', $sortDirection = 'asc');
        $this->assertEquals($page, $paginator->currentPage());
        $this->assertEquals('second', $paginator->first()->getName());

        $paginator = $this->repository->paginate($perPage = 1, $page = 3, $sortBy = 'name', $sortDirection = 'asc');
        $this->assertEquals($page, $paginator->currentPage());
        $this->assertEquals('test', $paginator->first()->getName());

        $paginator = $this->repository->paginate($perPage = 1, $page = 1, $sortBy = 'name', $sortDirection = 'desc');
        $this->assertEquals('test', $paginator->first()->getName());
    }

    /**
     * Test count the number of models.
     *
     * @return void
     */
    public function testCount()
    {
        $this->assertEquals(1, $this->repository->count());
        $this->assertEquals(1, $this->repository->includeSoftDeleted()->count());

        $this->repository->delete($this->model);
        $this->assertEquals(0, $this->repository->count());
        $this->assertEquals(1, $this->repository->includeSoftDeleted()->count());

        $this->repository->forceDelete($this->model);
        $this->assertEquals(0, $this->repository->count());
        $this->assertEquals(0, $this->repository->includeSoftDeleted()->count());
    }
}
