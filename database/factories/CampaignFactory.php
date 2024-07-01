<?php

use Faker\Generator as Faker;

$factory->define(Campaign::class, function (Faker $faker) {
    return [
        'title' => ucwords($faker->words(3, true)),
        'startDate' => $faker->dateTimeBetween('-30 days', '-1 day'),
        'endDate' => $faker->dateTimeBetween('tomorrow', '+30 days'),
    ];
});
