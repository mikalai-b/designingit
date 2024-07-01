<?php

use Faker\Generator as Faker;

$factory->define(State::class, function (Faker $faker) {
    return [
        'id' => ucfirst($faker->randomLetter.$faker->randomLetter),
        'name' => $faker->word(),
    ];
});
