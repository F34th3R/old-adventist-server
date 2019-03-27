<?php

use Faker\Generator as Faker;

$factory->define(App\Group::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'code' => 'G' . $faker->unique()->numberBetween($min = 10000, $max = 99999),
        'user_id' => $faker->unique()->numberBetween($min = 200, $max = 400),
        'union_id' => $faker->numberBetween($min = 1, $max = 200),
    ];
});
