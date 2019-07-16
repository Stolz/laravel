<?php

namespace Tests\Unit\Console\Commands;

use App\Repositories\Contracts\NotificationRepository;
use Mockery as m;
use Tests\TestCase;

class PurgeNotificationsTest extends TestCase
{
    /**
     * Age of the notifications to purge expresed in number of days.
     *
     * @const int
     */
    const DAYS = 10;

    /**
     * Test notifications are purged.
     *
     * @return void
     */
    public function testPurged()
    {
        $this->mock(NotificationRepository::class, function ($mock) {
            $date = m::on(function ($date) {
                return $date instanceof \Carbon\Carbon and $date->diffInDays() === static::DAYS;
            });
            $mock->shouldReceive('deleteReadOlderThan')->once()->with($date)->andReturn(123);
        });

        $this->runCommand()->expectsOutput('123 notifications deleted');
    }

    /**
     * Run the command under tests.
     *
     * @return self
     */
    protected function runCommand()
    {
        return $this->artisan('notifications:purge', ['--days' => static::DAYS])->assertExitCode(0);
    }
}
