<?php

namespace Tests\Unit\Console\Commands;

use App\Models\User;
use App\Repositories\Contracts\RoleRepository;
use App\Repositories\Contracts\UserRepository;
use Mockery as m;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    /**
     * Test user is not created when there are no roles available.
     *
     * @return void
     */
    public function testNoRolesDetected()
    {
        $this->mock(RoleRepository::class, function ($mock) {
            $mock->shouldReceive('all')->once()->andReturn(collect());
        });
        $this->mock(UserRepository::class, function ($mock) {
            $mock->shouldNotReceive('create');
        });

        $this->runCommand()->expectsOutput('No roles found. Is the database seeded?');
    }

    /**
     * Test user is created.
     *
     * @return void
     */
    public function testUserCreated()
    {
        $user = factory(User::class)->make([
            'password' => $password = 'testing!',
            'role' => $role = \App\Models\Role::make(['id' => 1]),
        ]);

        $this->mock(RoleRepository::class, function ($mock) use ($role) {
            $mock->shouldReceive('all')->once()->andReturn(collect([$role]));
        });
        $this->mock(UserRepository::class, function ($mock) use ($user) {
            $mock->shouldReceive('findBy')->once()->andReturn(null);
            $newUser = m::on(function ($newUser) use ($user) {
                return $newUser instanceof User and $newUser->getEmail() === $user->getEmail();
            });
            $mock->shouldReceive('create')->once()->with($newUser)->andReturn(true);
        });

        $this->runCommand()
        ->expectsQuestion('Enter user role Id', $user->getRole()->getId())
        ->expectsQuestion('Enter user e-mail address', $user->getEmail())
        ->expectsQuestion('Enter user full name', $user->getName())
        ->expectsQuestion('Enter user password', $password)
        ->expectsQuestion('About to create user. Do you wish to continue?', 'yes')
        ->expectsOutput('User successfully created');
    }

    /**
     * Run the command under tests.
     *
     * @return self
     */
    protected function runCommand()
    {
        return $this->artisan('user:create')->assertExitCode(0);
    }
}
