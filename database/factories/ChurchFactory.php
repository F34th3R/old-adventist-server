<?php

use Faker\Generator as Faker;

$factory->define(App\Church::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'code' => 'C' . $faker->unique()->numberBetween($min = 10000, $max = 99999),
        'user_id' => $faker->unique()->numberBetween($min = 400, $max = 600),
        'group_id' => $faker->numberBetween($min = 1, $max = 200),
    ];
});
