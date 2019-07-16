<?php

namespace Tests\Unit\Console\Commands;

use App\Repositories\Contracts\PermissionRepository;
use Tests\TestCase;

class SynchronizePermissionsTest extends TestCase
{
    /**
     * Test permissions are not synchronized.
     *
     * @return void
     */
    public function testNotSynchronized()
    {
        $this->mock(PermissionRepository::class, function ($mock) {
            $mock->shouldReceive('sync')->once()->andReturn(false);
        });

        $this->runCommand()->expectsOutput('Unable to synchronize permissions');
    }

    /**
     * Test permissions are synchronized.
     *
     * @return void
     */
    public function testSynchronized()
    {
        $this->mock(PermissionRepository::class, function ($mock) {
            $mock->shouldReceive('sync')->once()->andReturn(true);
        });

        $this->runCommand()->expectsOutput('Permissions successfully synchronized');
    }

    /**
     * Run the command under tests.
     *
     * @return self
     */
    protected function runCommand()
    {
        return $this->artisan('permissions:sync')->assertExitCode(0);
    }
}
