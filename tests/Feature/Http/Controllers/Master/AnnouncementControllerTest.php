<?php

namespace Tests\Feature\Http\Controller\Master;

use App\Traits\AttachesRepositories;
use Tests\TestCase;
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
        $route = route('master.announcement.index');
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'get');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-master-module', 'announcement-list']]);
        $response = $this->actingAs($user)->get($route);
        $response->assertOk();
        $response->assertSee('TEST BEACON index');
    }

    /**
     * Test display the specified announcement.
     *
     * @return void
     */
    public function testShow()
    {
        // Create a announcement
        $announcement = factory(\App\Models\Announcement::class)->make();
        $this->announcementRepository->create($announcement);

        // User without permissions
        $route = route('master.announcement.show', [$announcement->getId()]);
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'get');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-master-module', 'announcement-view']]);
        $response = $this->actingAs($user)->get($route);
        $response->assertOk();
        $response->assertSee('TEST BEACON show');
    }

    /**
     * Test show the form for creating a new announcement.
     *
     * @return void
     */
    public function testCreate()
    {
        // User without permissions
        $route = route('master.announcement.create');
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'get');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-master-module', 'announcement-create']]);
        $response = $this->actingAs($user)->get($route);
        $response->assertOk();
        $response->assertSee('TEST BEACON create');
    }

    /**
     * Tests show the form for editing the specified announcement.
     *
     * @return void
     */
    public function testEdit()
    {
        // Create a announcement
        $announcement = factory(\App\Models\Announcement::class)->make();
        $this->announcementRepository->create($announcement);

        // User without permissions
        $route = route('master.announcement.edit', [$announcement->getId()]);
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'get');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-master-module', 'announcement-update']]);
        $response = $this->actingAs($user)->get($route);
        $response->assertOk();
        $response->assertSee('TEST BEACON update');
    }

    /**
     * Tests store a newly created announcement in storage.
     *
     * @return void
     */
    public function testStore()
    {
        // User without permissions
        $route = route('master.announcement.store');
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'post');

        // User with permissions. Incomplete data
        $user = $this->createUser(['permissions' => ['use-master-module', 'announcement-create']]);
        $referer = route('master.announcement.create');
        $response = $this->actingAs($user)->from($referer)->post($route);
        $response->assertRedirect($referer);
        $response->assertSessionHasErrors();

        // User with permissions. Complete data
        $data = factory(\App\Models\Announcement::class)->raw();
        $response = $this->post($route, $data);
        $response->assertRedirect(route('master.announcement.index'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }

    /**
     * Tests update the specified announcement in storage.
     *
     * @return void
     */
    public function testUpdate()
    {
        // Create a announcement
        $announcement = factory(\App\Models\Announcement::class)->make();
        $this->announcementRepository->create($announcement);

        // User without permissions
        $route = route('master.announcement.update', [$id = $announcement->getId()]);
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'put');

        // User with permissions. Incomplete data
        $user = $this->createUser(['permissions' => ['use-master-module', 'announcement-update']]);
        $referer = route('master.announcement.edit', [$id]);
        $response = $this->actingAs($user)->from($referer)->put($route);
        $response->assertRedirect($referer);
        $response->assertSessionHasErrors();

        // User with permissions. Complete data
        $data = factory(\App\Models\Announcement::class)->raw();
        $response = $this->put($route, $data);
        $response->assertRedirect(route('master.announcement.show', [$id]));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }

    /**
     * Tests remove the specified announcement from storage.
     *
     * @return void
     */
    public function testDestroy()
    {
        // Create a announcement
        $announcement = factory(\App\Models\Announcement::class)->make();
        $this->announcementRepository->create($announcement);

        // User without permissions
        $route = route('master.announcement.destroy', [$announcement->getId()]);
        $this->assertRejectsUnauthorizedAccessToRoute($route, 'delete');

        // User with permissions
        $user = $this->createUser(['permissions' => ['use-master-module', 'announcement-delete']]);
        $referer = route('master.announcement.index');
        $response = $this->actingAs($user)->from($referer)->delete($route);
        $response->assertRedirect($referer);
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');
    }
}
