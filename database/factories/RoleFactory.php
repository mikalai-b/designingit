<?php

use Faker\Generator as Faker;

$factory->define(Role::class, function (Faker $faker) {
    return [
        'name' => ucfirst($faker->words(2, true)),
        'description' => $faker->sentence(),
    ];
});
