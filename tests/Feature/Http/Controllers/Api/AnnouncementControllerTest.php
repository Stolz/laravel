<?php

namespace Tests\Http\Controllers\Api;

use App\Models\Announcement;
use App\Traits\AttachesRepositories;
use Tests\Traits\CreatesUsers;
use Tests\Traits\RefreshDatabase;
use Tests\Traits\RejectsUnauthorizedRouteAccess;

class AnnouncementControllerTest extends TestCase
{
    use RefreshDatabase, AttachesRepositories, CreatesUsers, RejectsUnauthorizedRouteAccess;

    /**
     * Run before each test.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // Create user with no permissions
        $this->user = $this->createUser();
    }

    /**
     * Tests display a list of announcements.
     *
     * @return void
     */
    public function testIndex()
    {
        // User without permissions
        $route = route('api.announcement.index');
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'get', 'api');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-master-module', 'announcement-list']]);
        $this->actingAs($user, 'api');

        // Test empty list
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
        // Create a announcement
        $announcement = factory(Announcement::class)->make();
        $this->announcementRepository->create($announcement);

        // User without permissions
        $route = route('api.announcement.show', $announcement->getId());
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'get', 'api');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-master-module', 'announcement-view']]);
        $this->actingAs($user, 'api');

        // Test existing announcement
        $response = $this->get($route);
        $response->assertOk();
        $response->assertJson($announcement->jsonSerialize());

        // Test non existing announcement
        $route = route('api.announcement.show', ['random']);
        $response = $this->get($route);
        $response->assertNotFound();
    }

    /**
     * Tests store a newly created announcement in storage.
     *
     * @return void
     */
    public function testStore()
    {
        // User without permissions
        $route = route('api.announcement.store');
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'post', 'api');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-master-module', 'announcement-create']]);
        $this->actingAs($user, 'api');

        // Test with incomplete data
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
        // Create a announcement
        $announcement = factory(Announcement::class)->make();
        $this->announcementRepository->create($announcement);

        // User without permissions
        $route = route('api.announcement.update', $announcement->getId());
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'put', 'api');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-master-module', 'announcement-update']]);
        $this->actingAs($user, 'api');

        // Test existing announcement with incomplete data
        $response = $this->put($route);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);

        // Test existing announcement with complete data
        $data = factory(Announcement::class)->raw();
        $response = $this->put($route, $data);
        $response->assertOk();
        $response->assertJson(['updated' => true]);

        // Test non existing announcement
        $route = route('api.announcement.update', ['random']);
        $response = $this->put($route);
        $response->assertNotFound();
    }

    /**
     * Tests remove the specified announcement from storage.
     *
     * @return void
     */
    public function testDestroy()
    {
        // Create a announcement
        $announcement = factory(Announcement::class)->make();
        $this->announcementRepository->create($announcement);

        // User without permissions
        $route = route('api.announcement.destroy', $announcement->getId());
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'delete', 'api');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-master-module', 'announcement-delete']]);
        $this->actingAs($user, 'api');

        // Test existing announcement
        $response = $this->delete($route);
        $response->assertOk();
        $response->assertJson(['deleted' => true]);

        // Test non existing announcement
        $route = route('api.announcement.destroy', ['random']);
        $response = $this->delete($route);
        $response->assertNotFound();
    }
}
