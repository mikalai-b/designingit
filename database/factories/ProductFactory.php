<?php

use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'id' => $faker->uuid(),
        'prescriptionOnly' => true,
        'name' => ucwords($faker->words(3, true)),
        'strength' => sprintf('%smg', $faker->randomElement([1, 5, 10, 20, 50, 100, 250, 500])),
        'quantity' => sprintf('%s ct', $faker->randomElement([15, 20, 25, 30, 40, 42, 45, 60, 90])),
        'info' => $faker->sentence(),
        'price' => $faker->numberBetween(20, 120),
        'grouponPrice' => $faker->numberBetween(20, 120),
        'ndcNumber' => $faker->numerify('#####-####-##'),
        'thumbnail' => sprintf('/images/thumbnails/%s.jpg', $faker->slug(3)),
        'slug' => $faker->slug(3),
        'grouponContent' => $faker->paragraph(),
        'invalidCodeMessage' => $faker->paragraph(),
        'refillsContent' => $faker->paragraph(),
    ];
});
