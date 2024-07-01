<?php

use Faker\Generator as Faker;

$factory->define(Person::class, function (Faker $faker) {
    return [
        'firstName' => $faker->firstName(),
        'middleName' => $faker->firstName(),
        'lastName' => $faker->lastName(),
        'gender' => $faker->randomElement(['F', 'M']),
        'dateOfBirth' => new DateTime($faker->date('Y-m-d', '2000-01-01')),
        'email' => $faker->email(),
        'phone' => $faker->phoneNumber(),
        'addressLine1' => $faker->streetAddress(),
        'city' => $faker->city(),
        'postalCode' => $faker->postcode(),
        'dateCreated' => new DateTime('now')
    ];
});
