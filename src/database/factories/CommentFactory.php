<?php
use App\Comment;
use App\Incident;
use App\Photo;
use App\Patron;
use App\User;

$factory->define(Comment::class, function (Faker\Generator $faker) {
	$commentables = [
		'incident' => Incident::class,
		'photo' => Photo::class,
		'patron' => Patron::class,
	];
	$commentable_model = $faker->randomElement($commentables);
	$commentable = $commentable_model::all()->random();

	return [
        'user_id' => $faker->numberBetween(1, User::all()->count()),
        'comment' => $faker->sentence(15),
        'commentable_type' => array_search($commentable_model, $commentables),
        'commentable_id' => $commentable->id,
    ];
});