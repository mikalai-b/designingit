<?php

use Faker\Generator as Faker;

$factory->define(LineItem::class, function (Faker $faker) {
    return [
        'period' => $faker->randomElement([30, 60, 90, 120]),
        'price' => $faker->numberBetween(20, 120),
    ];
});
