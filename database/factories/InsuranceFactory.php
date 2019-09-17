<?php

namespace database\factories;

use App\Models\Farmer;
use Faker\Generator as Faker;

class InsuranceFactory
{
    public static function getFactory(Faker $faker)
    {
        $cropsInsured = ['Maize, Beans', 'Rice', 'Popcorn', 'Coffee', 'Cassava'];
        $cropInsured = $faker->randomElement($cropsInsured);
        return [
            'phone' => $faker->phoneNumber,
            'name' => $faker->name,
            'vaId' => 'AK/MA/0421/0001',
            'type' => 'insurance',
            'eloquent_type' => 'insurance',
            'status' => 'new',
            'history' =>
                [
                    [
                        'planting_date' => '',
                        'variety' => 'longe10',
                        'season' => 1,
                        'acreage_cultivated' => 2,
                        'harvesting_date' => '',
                        'cost_of_production' => 800,
                        'peril' => ['Drought'],
                        'price_per_kg' => 500,
                        'yield_produced' => 800,
                    ],
                    [
                        'planting_date' => '',
                        'variety' => 'longe2',
                        'season' => 2,
                        'acreage_cultivated' => 2,
                        'harvesting_date' => '',
                        'cost_of_production' => 20000,
                        'peril' => [],
                        'price_per_kg' => 500,
                        'yield_produced' => 20000,
                    ],
                    [
                        'planting_date' => '',
                        'variety' => 'longe10',
                        'season' => 3,
                        'acreage_cultivated' => 2,
                        'harvesting_date' => '',
                        'cost_of_production' => 1500,
                        'peril' => ['Hail Damage'],
                        'price_per_kg' => 2000,
                        'yield_produced' => 1500,
                    ],
                ],
            'user_id' => function () {
                return factory(Farmer::class)->create()->_id;
            },
            '_id' => $faker->uuid,
            'request' =>
                [
                    'seed_sources' => '10',
                    'av_yield' => '100000',
                    'variety' => 'longe10',
                    'crop_insured' => $cropInsured,
                    'acreage_insured' => '2',
                ],
            'time' => '2017-10-20T10:58:33',
            'photo' => '/uploads/5vknw209949.png',
        ];
    }
}
