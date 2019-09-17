<?php

namespace database\factories;

use Faker\Generator as Faker;

class AdminFactory
{
    public static function getFactory(Faker $faker)
    {
        return [
            '_id' => $faker->uuid,
            'eloquent_type' => 'admin',
            'adminRole' => 'Super Admin',
            'firstname' => 'Johnson',
            'lastname' => 'Smith',
            'email' => 'admin2020@gmail.com',
            'type' => 'admin',
            'password' => 'admin2020'
        ];
    }
}
