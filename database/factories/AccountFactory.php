<?php

namespace database\factories;

use Faker\Generator as Faker;

class AccountFactory
{
    public static function getFactory(Faker $faker)
    {
        return [
            'phone' => $faker->phoneNumber,
            'date' => '2019-02-21 08:51:05.791554',
            'type' => 'account',
            'eloquent_type' => 'account',
            'enterprise' => 'farmer',
            '_id' => $faker->uuid,
        ];
    }
}
