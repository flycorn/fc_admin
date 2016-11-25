<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

//$factory->define(App\User::class, function (Faker\Generator $faker) {
//    return [
//        'name' => $faker->name,
//        'email' => $faker->safeEmail,
//        'password' => bcrypt(str_random(10)),
//        'remember_token' => str_random(10),
//    ];
//});

$factory->define(App\Http\Models\BackstageUsers::class, function (Faker\Generator $faker) {
    $salt = getSalt();
    $pwd = '123456';
    return [
        'username' => $faker->userName,
        'nickname' => $faker->name,
        'email' => $faker->safeEmail,
        'salt' => $salt,
        'password' => Crypt::encrypt($pwd.$salt),
    ];
});