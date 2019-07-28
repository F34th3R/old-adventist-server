<?php

use Faker\Generator as Faker;

$factory->define(App\Advertisement::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'code' => 'A' . $faker->unique()->numberBetween($min = 1000, $max = 9999),
        'department_id' => $faker->numberBetween($min = 1, $max = 800),
        'publicationDate' => $faker->date($format = 'd-m-Y', $max = 'now'),
        'eventDate' => $faker->date($format = 'd-m-Y', $max = 'now'),
        'description' => $faker->text($maxNbChars = 800),
        'fragment' => $faker->text($maxNbChars = 90),
        'published' => $faker->numberBetween($min = 0, $max = 1),
        'image_id' => $faker->numberBetween($min = 1, $max = 100),
        'time' => $faker->time($format = 'H:i:s', $max = 'now'),
        'place' => $faker->word,
        'guest' => $faker->word,
    ];
});
