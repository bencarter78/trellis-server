<?php

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
         'email' => $faker->unique()->safeEmail,
        'password' => $password ?: bcrypt(config('trellis.demo.user.password')),
        'remember_token' => str_random(10),
        'api_token' => str_random(60),
    ];
});

$factory->state(App\User::class, 'demo', function (Faker\Generator $faker) {
    return [
        'email' => config('trellis.demo.user.email'),
    ];
});
