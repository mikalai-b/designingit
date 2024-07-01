<?php

use Faker\Generator as Faker;

$factory->define(Order::class, function (Faker $faker) {
    return [
        'state' => $faker->stateAbbr(),
        'status' => $faker->randomElement([
            Order::STATUS_CLOSED, Order::STATUS_NEW, Order::STATUS_OPEN, Order::STATUS_PENDING
        ]),
        
    ];
});
