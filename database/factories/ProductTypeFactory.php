<?php

use Faker\Generator as Faker;

$factory->define(ProductType::class, function (Faker $faker) {
    return [
        'name' => ucwords($faker->words(3, true)),
        'directions' => $faker->sentence(),
        'instructionsTemplate' => $faker->paragraphs(3, true),
        'approvedTemplate' => $faker->paragraphs(3, true),
        'defaultRefills' => 11,
        'defaultExpiration' => 12,
        'defaultPeriod' => $faker->randomElement([30, 60, 90]),
        'availableDashboardPeriods' => $faker->randomElements([30, 60, 90, 120, 150, 180], 3),
        'requireAutoRenewal' => true,
    ];
});
