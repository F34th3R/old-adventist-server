<?php

use Faker\Generator as Faker;

$factory->define(App\Department::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'code' => 'D' . $faker->unique()->numberBetween($min = 1000, $max = 9999),
        'user_id' => $faker->numberBetween($min = 1, $max = 600),
    ];
});
