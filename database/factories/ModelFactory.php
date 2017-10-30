<?php

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
$factory->define(\App\Models\Auth\User\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(\App\Models\Item::class, function(Faker\Generator $faker){
	
	return [
		'name' => $faker->word,
		'description' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true) 
	];
});

$factory->define(\App\Models\Product::class, function(Faker\Generator $faker){
	
	return [
		'description' => 'Pack de 5 post',
        'price' => '100$',
        'video_route' => 'resources/mov1.mp4',
        'item_id' => "2",  
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
	];
});
