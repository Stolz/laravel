<?php

namespace Tests\Http\Controllers\Api;

use App\Models\Announcement;
use App\Traits\AttachesRepositories;
use Tests\Traits\CreatesUsers;
use Tests\Traits\RefreshDatabase;

class AnnouncementControllerTest extends TestCase
{
    use RefreshDatabase, AttachesRepositories, CreatesUsers;

    /**
     * Run before each test.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // Create user with all permissions ...
        $this->user = $this->createUser([], ['name' => 'Admin']);

        // ... and authenticate it
        $this->actingAs($this->user, 'api');
    }

    /**
     * Tests display a list of announcements.
     *
     * @return void
     */
    public function testIndex()
    {
        // Test empty list
        $route = route('api.announcement.index');
        $response = $this->get($route);
        $response->assertOk();
        $response->assertJsonStructure(static::PAGINATION_STRUCTURE);
        $response->assertJsonCount(0, 'data');

        // Create test announcements
        $announcementFoo = factory(Announcement::class)->make();
        $announcementBar = factory(Announcement::class)->make();
        $this->announcementRepository->create($announcementFoo);
        $this->announcementRepository->create($announcementBar);

        // Test non empty list
        $response = $this->get($route);
        $response->assertOk();
        $response->assertJsonStructure(static::PAGINATION_STRUCTURE);
        $response->assertJsonCount(2, 'data');

        // Test sorting results
        $route = route('api.announcement.index', ['sort_by' => 'id', 'sort_dir' => 'desc']);
        $response = $this->get($route);
        $response->assertOk();
        $response->assertJsonStructure(static::PAGINATION_STRUCTURE);
        $response->assertJsonCount(2, 'data');
        $this->assertEquals($announcementBar->getId(), $response->json('data')[0]['id']);
    }

    /**
     * Test display the specified announcement.
     *
     * @return void
     */
    public function testShow()
    {
        // Test non existing announcement
        $route = route('api.announcement.show', ['random']);
        $response = $this->get($route);
        $response->assertNotFound();

        // Create a announcement
        $announcement = factory(Announcement::class)->make();
        $this->announcementRepository->create($announcement);

        // Test existing announcement
        $route = route('api.announcement.show', $announcement->getId());
        $response = $this->get($route);
        $response->assertOk();
        $response->assertJson($announcement->jsonSerialize());
    }

    /**
     * Tests store a newly created announcement in storage.
     *
     * @return void
     */
    public function testStore()
    {
        // Test with incomplete data
        $route = route('api.announcement.store', ['random']);
        $response = $this->post($route);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);

        // Test with complete data
        $data = factory(Announcement::class)->raw();
        $response = $this->post($route, $data);
        $response->assertStatus(201);
        $response->assertJsonStructure(['id', 'created']);
        $response->assertJson(['created' => true]);
    }

    /**
     * Tests update the specified announcement in storage.
     *
     * @return void
     */
    public function testUpdate()
    {
        // Test non existing announcement
        $route = route('api.announcement.update', ['random']);
        $response = $this->put($route);
        $response->assertNotFound();

        // Create a announcement
        $announcement = factory(Announcement::class)->make();
        $this->announcementRepository->create($announcement);

        // Test existing announcement with incomplete data
        $route = route('api.announcement.update', $announcement->getId());
        $response = $this->put($route);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);

        // Test existing announcement with complete data
        $data = factory(Announcement::class)->raw();
        $response = $this->put($route, $data);
        $response->assertOk();
        $response->assertJson(['updated' => true]);
    }

    /**
     * Tests remove the specified announcement from storage.
     *
     * @return void
     */
    public function testDestroy()
    {
        // Test non existing announcement
        $route = route('api.announcement.destroy', ['random']);
        $response = $this->delete($route);
        $response->assertNotFound();

        // Create a announcement
        $announcement = factory(Announcement::class)->make();
        $this->announcementRepository->create($announcement);

        // Test existing announcement
        $route = route('api.announcement.destroy', $announcement->getId());
        $response = $this->delete($route);
        $response->assertOk();
        $response->assertJson(['deleted' => true]);
    }
}
