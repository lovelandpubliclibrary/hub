<?php

$factory->define(App\Comment::class, function (Faker\Generator $faker) {
	return [
        'user_id' => $faker->numberBetween(1, App\User::all()->count()),
        'incident_id' => $faker->numberBetween(1, App\Incident::all()->count()),
        'comment' => $faker->sentence(15),
    ];
});