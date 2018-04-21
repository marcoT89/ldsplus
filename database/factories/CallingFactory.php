<?php

use Faker\Generator as Faker;
use App\Models\Organization;

$factory->define(App\Models\Calling::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
        'organization_id' => function () {
            return factory(Organization::class)->create()->id;
        }
    ];
});

$factory->state(App\Models\Calling::class, 'male', function (Faker $faker) {
    return [
        'gender' => 'male',
    ];
});

$factory->state(App\Models\Calling::class, 'female', function (Faker $faker) {
    return [
        'gender' => 'female',
    ];
});

$factory->state(App\Models\Calling::class, 'both', function (Faker $faker) {
    return [
        'gender' => 'both',
    ];
});
