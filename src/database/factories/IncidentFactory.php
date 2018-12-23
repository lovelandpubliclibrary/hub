<?php

$factory->define(App\Incident::class, function (Faker\Generator $faker) {
	return [
        'date' => $faker->date(),
        'time' => $faker->time(),
        'title' => preg_replace('/[^a-z0-9]+/i', ' ', $faker->realText(rand(10, 50), 1)),
        'description' => $faker->paragraphs(rand(1, 5), true),
        'user_id' => rand(1, App\User::all()->count()),
    ];
});
