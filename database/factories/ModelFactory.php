<?php

use App\User;
use Carbon\Carbon;

$factory->define(App\Milestone::class, function (Faker\Generator $faker) {
    return [
        'project_id' => function () {
            return factory(App\Project::class)->create()->id;
        },
        'uid' => str_random(10),
        'name' => $faker->name,
        'description' => $faker->paragraph,
        'due_on' => Carbon::now()->addMonth(),
    ];
});

$factory->define(App\Objective::class, function (Faker\Generator $faker) {
    return [
        'project_id' => function () {
            return factory(App\Project::class)->create()->id;
        },
        'uid' => str_random(10),
        'name' => $faker->sentence,
        'is_complete' => null,
        'due_on' => Carbon::now()->addMonth(),
    ];
});

$factory->define(App\Objective::class, 'completed', function (Faker\Generator $faker) {
    return ['is_complete' => Carbon::yesterday()];
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
        'due_on' => Carbon::now()->addMonth(),
    ];
});

$factory->define(App\Stream::class, function (Faker\Generator $faker) {
    return [
        'team_id' => function () {
            return factory(App\Team::class)->create()->id;
        },
        'project_id' => function () {
            return factory(App\Project::class)->create()->id;
        },
        'uid' => str_random(10),
        'name' => $faker->name,
    ];
});

$factory->define(App\Task::class, function (Faker\Generator $faker) {
    return [
        'uid' => str_random(10),
        'assigned_to' => function () {
            return factory(App\User::class)->create()->id;
        },
        'name' => ucwords($faker->words(4, true)),
        'due_on' => Carbon::now()->addMonth(),
        'is_complete' => null,
    ];
});

$factory->state(App\Task::class, 'project', function () {
    return [
        'owner_id' => function () {
            return factory(App\Project::class)->create()->id;
        },
        'owner_type' => App\Project::class,
    ];
});

$factory->define(App\Team::class, function (Faker\Generator $faker) {
    return [
        'uid' => str_random(10),
        'name' => ucwords($faker->words(2, true)),
        'owner_id' => function () {
            return factory(User::class)->create()->id;
        },
    ];
});

$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: bcrypt(config('trellis.demo.user.password')),
        'username' => str_random(10),
        'remember_token' => str_random(10),
    ];
});

$factory->state(App\User::class, 'demo', function (Faker\Generator $faker) {
    return [
        'email' => config('trellis.demo.user.email'),
    ];
});
