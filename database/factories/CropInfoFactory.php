<?php

namespace database\factories;

use Faker\Generator as Faker;

class CropInfoFactory
{
    public static function getFactory(Faker $faker)
    {
        return [
            'crop' => 'Beans',
            'type' => 'cropinf',
            'eloquent' => 'cropinf',
            'description' =>
                [
                    'Identify places with fertile soils, deep soil support proper root development. ',
                    'Flat or gently sloping surface prevents soil from being carried by rain water. ',
                    'Avoid wetlands as they are prone to flooding and high humidity (a lot of water in the air) .',
                    'Do not plant beans on sandy and clay soils ',
                    'Avoid planting beans in areas with problematic weeds such as couch grass. '
                ],
            '_id' => $faker->uuid,
            'photo_url' => '/images/86aa2e67-ed0b-47d2-abad-8856dc8bc195.png',
            'title' => 'Site selection',
            'purpose' => 'Land preparation',
        ];
    }
}
