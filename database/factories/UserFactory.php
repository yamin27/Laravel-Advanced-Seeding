<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/
    
    $factory->define(App\User::class, function (Faker\Generator $faker) {
        static $password;
        return [
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'avatar' => 'https://randomuser.me/api/portraits/' . $faker->randomElement(['men', 'women']) . 'men/' . rand(1,99) . '.jpg',
            'password' => $password ?: $password = bcrypt('secret'),
            'remember_token' => str_random(10),
        ];
    });
    
    $factory->define(App\Channel::class, function(\Faker\Generator $faker) {
        return [
            'name' => $faker->company,
            'logo' => $faker->imageUrl(60, 60),
            'cover' => $faker->imageUrl(),
            'about' => $faker->text(rand(100, 500)),
            'user_id' => function () {
                return factory(App\User::class)->create()->id;
            }
        ];
    });
    
    $factory->define(App\Video::class, function(\Faker\Generator $faker) {
        return [
            'title' => $faker->text(),
            'description' => $faker->realText(rand(80, 600)),
            'published' => $faker->boolean(),
            'url' => $faker->url,
            'thumbnail' => $faker->imageUrl(640, 480, null, true),
            'allow_comments' => $faker->boolean(80),
            'views' => rand(0, 872323)
        ];
    });
    
    $factory->define(App\Comment::class, function(\Faker\Generator $faker) {
        return [
            'body' => $faker->realText(rand(10, 300)),
            'video_id' => function () {
                // Get random video id
                return App\Video::inRandomOrder()->first()->id;
            },
            'user_id' => function () {
                // Get random user id
                return App\User::inRandomOrder()->first()->id;
            }
        ];
    });

