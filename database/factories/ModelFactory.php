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

$factory->define(App\MailQueue::class, function(Faker\Generator $f) {
	return [
		'entry_id' => 1,
		'message_id' => 1,
		'status' => 8,
		'Report' => 'Dummy Data',
		'created_at' => Carbon::now()->toDateTimeString(),
		'updated_at' => Carbon::now()->toDateTimeString(),

	];
});
$factory->define(App\Stat::class, function(Faker\Generator $faker) {
	$dt = $faker->dateTimeThisMonth();
	return [
		'deliveries' => rand(0, 100),
		'spam_complaints' => rand(0, 100),
		'clicks' => rand(0,100),
		'opens' => rand(0,100),
		'created_at' => $dt,
		'updated_at' => $dt
	];
});

$factory->define(App\MailList::class, function(Faker\Generator $faker) {

	return [
		'title' => $faker->words(rand(3,5), true),
		'description' => $faker->words(rand(5,10), true),
		'from_name' => $faker->firstName . ' ' . $faker->lastName,
		'from_email' => $faker->email
	];
});

$factory->define(App\Entry::class, function(Faker\Generator $faker) {
	return [
		'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'segment' => 'asdf',
        'company_name' => str_replace(',', ' ', $faker->company),
        'city' => $faker->city,
        'state' => $faker->state,
        'zip' => $faker->postcode,
        'phone' => $faker->phoneNumber,
		'email' => $faker->unique()->safeEmail,
		'clicked_unsubscribe' => 0
	];
});

$factory->define(App\Message::class, function(Faker\Generator $faker) {
	return [
		'name' => $faker->words(rand(3,5), true),
		'content' => '<a href="http://example.com">click me</a>'.$faker->words(rand(100,500), true),
		'subject' => $faker->words(rand(5, 10), true),
		'message_time' => Carbon::now()->addMinutes(2)->toTimeString(),
	];
});

