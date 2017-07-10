<?php

use App\User;
use Carbon\Carbon;

$factory->define(App\Project::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->words(3, true),
        'description' => $faker->paragraph,
    ];
});

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: bcrypt(config('trellis.demo.user.password')),
        'remember_token' => str_random(10),
    ];
});

$factory->state(App\User::class, 'demo', function (Faker\Generator $faker) {
    return [
        'email' => config('trellis.demo.user.email'),
    ];
});

$factory->define(App\Team::class, function (Faker\Generator $faker) {
    return [
        'uid' => strtolower(str_random(7) . Carbon::now()->timestamp),
        'name' => 'My Amazing Team',
        'owner_id' => function () {
            return factory(User::class)->create()->id;
        },
    ];
});
