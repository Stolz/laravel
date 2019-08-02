<?php

use App\Models\Announcement;
use Faker\Generator as Faker;

$factory->define(Announcement::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence,
        'description' => $faker->text,
        'active' => $faker->boolean,
    ];
});
