<?php

namespace Tests\Unit\Repositories\Contracts;

use App\Models\User;
use App\Traits\AttachesRepositories;
use Tests\RefreshDatabase;
use Tests\TestCase;

/**
 * This class is meant to tests both \App\Repositories\Contracts\{ModelRepository,SoftDeletableModelRepository}.
 * Since the base model is an abstract class it has no repository. Therefore the user model repository contract
 * is used instead since it implements both of the interfaces under test.
 */
class ModelRepositoryTest extends TestCase
{
    use RefreshDatabase, AttachesRepositories;

    /**
     * Run before each test.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        // Create test role
        $role = \App\Models\Role::make(['name' => str_random(6)]);
        $this->roleRepository->create($role);

        // Create test user
        $this->model = factory(User::class)->make(['name' => 'test', 'role' => $role]);
        $this->userRepository->create($this->model);
    }

    /**
     * Test retrieve all models.
     *
     * @return void
     */
    public function testAll()
    {
        $this->assertEquals(1, $this->userRepository->all()->count());
        $this->assertEquals(1, $this->userRepository->includeSoftDeleted()->all()->count());

        $this->userRepository->delete($this->model);
        $this->assertEquals(0, $this->userRepository->all()->count());
        $this->assertEquals(1, $this->userRepository->includeSoftDeleted()->all()->count());

        $this->userRepository->forceDelete($this->model);
        $this->assertEquals(0, $this->userRepository->all()->count());
        $this->assertEquals(0, $this->userRepository->includeSoftDeleted()->all()->count());
    }

    /**
     * Test save a new model.
     *
     * @return void
     */
    public function testCreate()
    {
        $model = with(clone $this->model)->setEmail(str_random());
        $this->assertTrue($this->userRepository->create($model));
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
        $this->assertTrue($this->userRepository->update($this->model->setName(str_random())));
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
        $this->assertTrue($this->userRepository->delete($this->model));
        $this->assertNotNull($this->userRepository->includeSoftDeleted()->find($this->model->getId()));
    }

    /**
     * Test force delete a model.
     *
     * @return void
     */
    public function testForceDelete()
    {
        $this->assertTrue($this->userRepository->forceDelete($this->model));
        $this->assertNull($this->userRepository->includeSoftDeleted()->find($this->model->getId()));
    }

    /**
     * Test restore a soft deleted model.
     *
     * @return void
     */
    public function testRestore()
    {
        $this->userRepository->delete($this->model);
        $this->assertTrue($this->userRepository->restore($this->model));
        $this->assertNull($this->model->getDeletedAt());
    }

    /**
     * Test retrieve a single model by its primary key.
     *
     * @return void
     */
    public function testFind()
    {
        $this->assertNull($this->userRepository->find(null));
        $this->assertEquals($this->model->getId(), $this->userRepository->find($this->model->getId())->getId());
        $this->userRepository->delete($this->model);
        $this->assertNull($this->userRepository->find($this->model->getId()));
        $this->assertNotNull($this->userRepository->includeSoftDeleted()->find($this->model->getId()));
    }

    /**
     * Test retrieve a single model by a given unique field.
     *
     * @return void
     */
    public function testFindBy()
    {
        $this->assertNull($this->userRepository->findBy('name', null));
        $this->assertEquals('test', $this->userRepository->findBy('name', 'test')->getName());
        $this->userRepository->delete($this->model);
        $this->assertNull($this->userRepository->findBy('name', 'test'));
        $this->assertNotNull($this->userRepository->includeSoftDeleted()->findBy('name', 'test'));
    }

    /**
     * Test count the number of models.
     *
     * @return void
     */
    public function testCount()
    {
        $this->assertEquals(1, $this->userRepository->count());
        $this->assertEquals(1, $this->userRepository->includeSoftDeleted()->count());

        $this->userRepository->delete($this->model);
        $this->assertEquals(0, $this->userRepository->count());
        $this->assertEquals(1, $this->userRepository->includeSoftDeleted()->count());

        $this->userRepository->forceDelete($this->model);
        $this->assertEquals(0, $this->userRepository->count());
        $this->assertEquals(0, $this->userRepository->includeSoftDeleted()->count());
    }
}
