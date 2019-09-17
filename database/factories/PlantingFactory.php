<?php

namespace database\factories;

use App\Models\Farmer;
use App\Models\MasterAgent;
use App\Models\VillageAgent;
use Faker\Generator as Faker;

class PlantingFactory
{
    public static function getFactory(Faker $faker)
    {
        $districts = ['Rukungiri', 'Rubirizi', 'Ntoroko', 'Buhweju', 'Bushenyi'];
        $district = $faker->randomElement($districts);
        return [
            '_id' => $faker->uuid,
            'acreage' => '2',
            'district' => $district,
            'eloquent_type' => 'planting',
            'name' => $faker->name,
            'payment' => 'cash',
            'status' => 'new',
            'time' => '2018-11-20T18:55:01',
            'total' => 200000,
            'type' => 'planting',
            'unit_cost' => 100000,
            'user_id' => function () {
                return factory(Farmer::class)->create()->_id;
            },
            "vaId" => function () {
                return factory(VillageAgent::class)->create()->_id;
            },
            "ma_id" => function () {
                return factory(MasterAgent::class)->create()->_id;
            }
        ];
    }
}
