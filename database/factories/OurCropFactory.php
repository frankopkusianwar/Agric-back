<?php

namespace database\factories;

use Faker\Generator as Faker;

class OurCropFactory
{
    public static function getFactory(Faker $faker)
    {
        return [
            'type' => 'our_crops',
            'eloquent_type' => 'our_crops',
            '_id' => $faker->uuid,
            'photo_url' => '/images/ae086c56-12cd-43cd-8f97-0b0e1267ab6c.png',
            'DateUpdated' => '2/13/2019',
            'crop' => 'Beans',
        ];
    }
}
