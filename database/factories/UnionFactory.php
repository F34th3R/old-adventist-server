<?php

use Faker\Generator as Faker;

$factory->define(App\Union::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'code' => 'U' . $faker->unique()->numberBetween($min = 1000, $max = 9999),
        'user_id' => $faker->unique()->numberBetween($min = 1, $max = 200),
    ];
});
