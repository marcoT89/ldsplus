<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Ward::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
    ];
});
