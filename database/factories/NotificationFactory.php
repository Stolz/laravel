<?php

use App\Models\Notification;
use Faker\Generator as Faker;

$factory->define(Notification::class, function (Faker $faker) {
    return [
        'level' => $faker->randomElement(Notification::LEVELS),
        'message' => $faker->sentence,
        'action_text' => $faker->word,
        'action_url' => $faker->url,
    ];
});
