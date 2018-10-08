<?php

$factory->define(App\Comment::class, function (Faker\Generator $faker) {

	// pick a random number
	switch (rand(0,1)) {

		case 1:
			$key = 'incident_id';
			$count = App\Incident::all()->count();
			break;

		default:
			$key = 'patron_id';
			$count = App\Patron::all()->count();
	}

	return [
        'user_id' => $faker->numberBetween(1, App\User::all()->count()),
        $key => $faker->numberBetween(1, $count),
        'comment' => $faker->sentence(15),
    ];
});