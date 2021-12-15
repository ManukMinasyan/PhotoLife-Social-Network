<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'caption' => $faker->text(200),
        'member_id' => function () {
            return App\Models\Member::inRandomOrder()->first()->id;
        },
    ];
});
