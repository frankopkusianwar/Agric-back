<?php

namespace database\factories;

use App\Models\Farmer;
use App\Models\MasterAgent;
use App\Models\VillageAgent;
use Faker\Generator as Faker;

class SprayingFactory
{
    public static function getFactory(Faker $faker)
    {
        $districts = ['Rukungiri', 'Rubirizi', 'Ntoroko', 'Buhweju', 'Bushenyi'];
        $district = $faker->randomElement($districts);
        return [
            'phone' => $faker->phoneNumber,
            'unit_cost' => 100000,
            '_id' => $faker->uuid,
            'status' => 'new',
            'district' => $district,
            "vaId" => function () {
                return factory(VillageAgent::class)->create()->_id;
            },
            "ma_id" => function () {
                return factory(MasterAgent::class)->create()->_id;
            },
            'acreage' => '2',
            'total' => 200000,
            'name' => 'Anyipo Moureen',
            'type' => 'spraying',
            'eloquent_type' => 'spraying',
            'time' => '2018-11-19T15:40:25',
            'user_id' => function () {
                return factory(Farmer::class)->create()->_id;
            },
            'payment' => 'cash',
        ];
    }
}
