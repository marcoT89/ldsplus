<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Organization::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
        'gender_type' => collect(['male', 'female', 'both'])->random(),
    ];
});
