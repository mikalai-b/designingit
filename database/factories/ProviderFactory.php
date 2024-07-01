<?php

use Faker\Generator as Faker;

$factory->define(Provider::class, function (Faker $faker) {
    return [
        'dateCreated' => new DateTime(),
        'npiNumber' => $faker->numberBetween(10000000, 100000000),
    ];
});
