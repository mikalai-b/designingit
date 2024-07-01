<?php

use Faker\Generator as Faker;

$factory->define(Consultation::class, function (Faker $faker) {
    return [
        'dateCreated' => new DateTime(),
        'dateModified' => new DateTime(),
        'status' => $faker->randomElement([
            Consultation::STATUS_NEW,
            Consultation::STATUS_OPEN,
            Consultation::STATUS_PENDING,
            Consultation::STATUS_COMPLETED,
        ])
    ];
});
