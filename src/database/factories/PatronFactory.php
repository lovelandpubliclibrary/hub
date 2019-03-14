<?php

$factory->define(App\Patron::class, function (Faker\Generator $faker) {
	return [
        'first_name' => $faker->optional()->firstName(),
        'last_name' => $faker->optional()->lastName(),
        'description' => $faker->sentence(6),
        'card_number' => $faker->optional()->randomNumber(9),
    ];
});