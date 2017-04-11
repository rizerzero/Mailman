<?php

use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});


$factory->define(App\MailList::class, function(Faker\Generator $faker) {

	return [
		'title' => $faker->words(rand(3,5), true),
		'description' => $faker->words(rand(5,10), true),
	];
});

$factory->define(App\Entry::class, function(Faker\Generator $faker) {
	return [
		'name' => $faker->words(rand(3,5), true),
		'email' => $faker->unique()->safeEmail,
	];
});

$factory->define(App\Message::class, function(Faker\Generator $faker) {
	return [
		'name' => $faker->words(rand(3,5), true),

		'content' => '<a href="http://example.com">click me</a>'.$faker->words(rand(100,500), true),
		'subject' => $faker->words(rand(5, 10), true),
		'message_time' => Carbon::now()->addMinutes(2)->toTimeString()
	];
});
