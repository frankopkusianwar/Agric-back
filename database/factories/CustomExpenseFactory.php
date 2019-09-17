<?php

namespace database\factories;

use Faker\Generator as Faker;

class CustomExpenseFactory
{
    public static function getFactory(Faker $faker)
    {
        return [
            'type' => 'custom_expenses',
            'eloquent_type' => 'custom_expenses',
            'status' => 'new',
            'time' => '2018-11-27',
            'user_id' => 'AFAHAJOH788007645RUKNYA',
            '_id' => $faker->uuid,
            'vaId' => 'AK/MA/0421/0001',
            'cost' => 10000,
            'activity' => 'Planting',
        ];
    }
}
