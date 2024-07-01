<?php

use Faker\Generator as Faker;

$factory->define(Account::class, function (Faker $faker) {
    return [
        'password' => password_hash('boldrx', PASSWORD_BCRYPT),
        'customer' => $faker->numberBetween(10000000, 99999999),
        'dateCreated' => new DateTime(),
    ];
});
