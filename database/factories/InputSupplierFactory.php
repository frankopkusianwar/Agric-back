<?php

namespace database\factories;

use Faker\Generator as Faker;

class InputSupplierFactory
{
    public static function getFactory(Faker $faker)
    {
        $category = ['Seeds', 'Pesticides', 'Herbicides', 'Fertilizer', 'Tools'];
        $quantity = [9992, 1000, 3000, 1500];
        return [
            "_id" => $faker->uuid,
            "category" => $faker->randomElement($category),
            "quantity" => $faker->randomElement($quantity),
            "supplier" => "Hangzhou Agrochemicals (U) Ltd",
            "DateUpdated" => "11/9/2018",
            "DateAdded" => "2018-08-31",
            "crops" => ["beans", "soya"],
            "name" => "Beans Clean",
            "type" => "input",
            "eloquent_type" => "input",
            "description" => "Selective weed killer for beans and soya",
            "photo_url" => "/images/7e185f0a-cfc5-45a3-bb4d-6ef6535a5042.png",
            "price" => [38100, 21000],
            "unit" => ["1Litre", "500ml"],
        ];
    }
}
