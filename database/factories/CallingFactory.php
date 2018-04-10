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
