<?php

use Faker\Generator as Faker;

$factory->define(Offer::class, function (Faker $faker) {
    return [
        'price' => $faker->randomElement([$faker->numberBetween(20, 30), null]),
        'firstShipmentPrice' => $faker->randomElement([$faker->numberBetween(20, 30), null]),
    ];
});
