<?php

namespace database\factories;

use Faker\Generator as Faker;

class MilkLedgerFactory
{
    public static function getFactory(Faker $faker)
    {
        return [
            'quantity_supplied' => '80',
            'amount_expected' => '80000',
            'quantity_accepted' => '80',
            'quality_test' =>
                [
                    'alv' => '',
                    'temperature' => '',
                    'rezarin' => '',
                    'protein' => '',
                    'alcohol' => '4',
                    'freezing_point' => '',
                    'fat' => '',
                    'added_water' => '',
                    'snf' => '',
                    'conductivity' => '',
                    'density' => '5',
                ],
            'quantity_rejected' => '0',
            'ma_id' => 'AK/MA/0422',
            'farmer_gender' => 'male',
            'unit_price' => '1000',
            'date_supplied' => '2018-11-16',
            'farmer_id' => 'AFKAAJAV703369825KIRJK',
            'type' => 'milk_ledger',
            'eloquent_type' => 'milk_ledger',
            '_id' => $faker->uuid,
            'farmer_phone' => $faker->phoneNumber,
            'vaId' => 'AK/MA/0422/0002',
            'farmer_name' => $faker->name,
        ];
    }
}
