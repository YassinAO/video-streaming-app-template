<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Subscription;
use App\Channel;
use App\User;

$factory->define(Subscription::class, function (Faker $faker) {
    return [
        'user_id' => function(){
            return factory(User::class)->create()->id;
        },
        'channel_id' => function(){
            return factory(Channel::class)->create()->id;
        }
    ];
});
