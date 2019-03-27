<?php

use Faker\Generator as Faker;

$factory->define(App\Image::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'tag' => $faker->word,
        'path' => $faker->sentence($nbWords = 6, $variableNbWords = true)
    ];
});
