<?php

use App\Models\User;
use App\Repositories\Contracts\RoleRepository;
use Faker\Generator as Faker;

$roles = app(RoleRepository::class)->all();

$factory->define(User::class, function (Faker $faker) use ($roles) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => 'secret',
        'remember_token' => str_random(10),
        'role' => $roles->random(),
    ];
});
