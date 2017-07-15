<?php

use App\User;

$factory->define(App\Objective::class, function (Faker\Generator $faker) {
    return [
        'project_id' => function () {
            return factory(App\Project::class)->create()->id;
        },
        'name' => $faker->sentence,
    ];
});

$factory->define(App\Project::class, function (Faker\Generator $faker) {
    return [
        'owner_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'team_id' => function () {
            return factory(App\Team::class)->create()->id;
        },
        'uid' => str_random(10),
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
        'uid' => str_random(10),
        'name' => 'My Amazing Team',
        'owner_id' => function () {
            return factory(User::class)->create()->id;
        },
    ];
});
