<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\Customer::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->email,
        'country' => $faker->country,
        'gender' => random_int(0,1),
        'balance' => 0.0,
        'bonus_balance' => 0.0,
        'bonus_percentage' => randomBonusPercentage(),
        'deposit_count' => 0
    ];
});
