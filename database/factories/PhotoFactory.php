<?php

$factory->define(App\Photo::class, function (Faker\Generator $faker) {
	
	// generate random height and widths for each image, within likely constraints
	$width = rand(640, 1586);
	$height = rand(480, 1024);

	return [
        'incident_id' => rand(0, App\Incident::all()->count()),
        'filename'	  => $faker->unique()->image(public_path() . '/images/patrons/', $width, $height, 'people', false),
        'caption'	  => $faker->realText(),
    ];
});