<?php

use Faker\Generator as Faker;

$factory->define(Prescription::class, function (Faker $faker) {
    return [
        'dateCreated' => new DateTime(),
        'dateModified' => new DateTime(),
        'filled' => 1,
        'refills' => 11,
        'dateStart' => new DateTime(),
        'dateEnd' => new DateTime('now +6 months'),
        'status' => $faker->randomElement([Prescription::STATUS_ACTIVE, Prescription::STATUS_PAUSED]),
        'resupplyAttempts' => 0
    ];
});
