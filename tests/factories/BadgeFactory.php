<?php

use Faker\Generator as Faker;
use Humweb\Teams\Models\Team;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Team::class, function (Faker $faker) {

    return [
        'name'           => $faker->word,
        'description'    => $faker->sentence,
        'points'         => $faker->numberBetween(5, 20)
    ];
});
