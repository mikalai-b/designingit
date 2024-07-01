<?php

use Faker\Generator as Faker;

$factory->define(CouponCode::class, function (Faker $faker) {
    return [
        'code' => strtoupper($faker->lexify('?????????????')),
        'campaignDescription' => ucwords($faker->words(4, true)),
        'redeemed' => $faker->boolean(),
        'unlimited' => $faker->boolean(),
        'redemptionCount' => 0,
        'redemptionLimit' => null,
    ];
});
