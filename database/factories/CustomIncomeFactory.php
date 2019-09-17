<?php

namespace database\factories;

use Faker\Generator as Faker;

class CustomIncomeFactory
{
    public static function getFactory(Faker $faker)
    {
        return [
            'type' => 'custom_income',
            'eloquent_type' => 'custom_income',
            'status' => 'new',
            'time' => '2019-04-23',
            'user_id' => 'AFAHAJOH788007645RUKNYA',
            '_id' => $faker->uuid,
            'vaId' => 'AK/MA/0421/0001',
            'cost' => 6000,
            'activity' => 'Sale of cabbage seeds',
        ];
    }
}
