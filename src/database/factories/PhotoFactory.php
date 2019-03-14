<?php

$factory->define(App\Photo::class, function (Faker\Generator $faker) {
	
	// generate random height and widths for each image, within likely constraints
	$width = rand(640, 1586);
	$height = rand(480, 1024);

	$photos_path = '/app/public/photos/';
	$category = rand(0,4) ? 'people' : 'city';		// make 1/4 places

	return [
        'filename'	=> $faker->unique()->image(storage_path() . $photos_path, 
        							 		   $width, 
        							 		   $height, 
        							 		   $category, 
        							 		   false),
        'caption'	=> $faker->realText(),
    ];
});