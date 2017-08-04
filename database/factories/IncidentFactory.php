<?php

$factory->define(App\Incident::class, function (Faker\Generator $faker) {
	return [
        'date' => $faker->date(),
        'title' => preg_replace('/[^a-z0-9]+/i', ' ', $faker->realText(rand(10, 50), 1)),
        'description' => $faker->paragraphs(rand(1, 5), true),
        'patron_name' => $faker->optional()->name(),
        'card_number' => $faker->optional()->randomNumber(8),
        'patron_description' => $faker->optional()->sentence(6),
        'user_id' => rand(1, App\User::all()->count()),
    ];
});
