<?php

use App\Models\User;
use Faker\Generator as Faker;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => 'verysecret',
        'timezone' => $faker->timezone,
        'remember_token' => str_random(10),
    ];
});
