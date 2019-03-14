<?php

$factory->define(App\User::class, function (Faker\Generator $faker) {
	return [
        'name' => $faker->name(),
        'email'	=> $faker->unique()->safeEmail(),
        'password' => Hash::make($faker->password()),
        'created_at' => $faker->dateTime(),
    ];
});