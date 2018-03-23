<?php

namespace Tests\Unit\Repositories\Contracts;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Run before each test.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->repository = app(\App\Repositories\Contracts\UserRepository::class);
        $this->model = User::make(['name' => 'test', 'email' => 'test@test.com', 'password' => 'secret']);
        $this->repository->create($this->model);
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
        $id = $this->model->getId();
        $this->assertTrue($this->repository->create($this->model->setEmail(str_random())));
        $this->assertNotEquals($id, $this->model->getId());
        $this->assertNotNull($this->model->getCreatedAt());
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
