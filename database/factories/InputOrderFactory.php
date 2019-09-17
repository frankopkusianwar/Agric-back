<?php

namespace database\factories;

use App\Models\Farmer;
use App\Models\MasterAgent;
use App\Models\VillageAgent;
use Faker\Generator as Faker;

class InputOrderFactory
{
    public static function getFactory(Faker $faker)
    {
        $productsPoolOne = [
          "Blended fertilizer",
          "Maguguma",
          "Korn Kali",
          "Weed master"
        ];
        $productsPoolTwo = [
          "Dudu Kill",
          "Harvester",
          "Metrazin",
          "Dudu Cypher",
        ];
        $productOne = $faker->randomElement($productsPoolOne);
        $productTwo = $faker->randomElement($productsPoolTwo);
        $districts = ['Rukungiri', 'Rubirizi', 'Ntoroko', 'Buhweju', 'Bushenyi'];
        $district = $faker->randomElement($districts);
        $categories = ['Seeds', 'Herbicide', 'Pesticide', 'Fertilizer', 'Farming Tools'];
        $paymentMethods = ['mm', 'cash'];
        $totalCosts = [123000, 1000000, 756000, 1200000, 1400000, 1670000, 230000, 405000, 560000, 350000, 111000];
        $totalCost = $faker->randomElement($totalCosts);
        $paymentMethod = $faker->randomElement($paymentMethods);
        $date = $faker->dateTime()->format("Y-m-d H:i:s");
        return [
            "details" => [
                "district" => $district,
                "name" => $faker->name,
                "phone" => $faker->phoneNumber,
                "photo" => "",
                "time" => $date,
                "totalCost" => $totalCost,
                "totalItems" => 2
            ],
              "eloquent_type" => "order",
              "orders" => [
                [
                    "category" => $faker->randomElement($categories),
                    "price" => 10000,
                    "product" => $productOne,
                    "qty" => 165,
                    "src" => "http://138.197.220.176:3000/assets/images/f9ab1da7-cd63-4e3d-8a96-b1c7dcc2cb42.png",
                    "stock" => "",
                    "supplier" => "World Food Program",
                    "unit" => "50 Kgs"
                ],
                [
                    "category" => $faker->randomElement($categories),
                    "price" => 10000,
                    "product" => $productTwo,
                    "qty" => 2,
                    "src" => "http://138.197.220.176:3000/assets/images/Jmugo75136.png",
                    "stock" => "",
                    "supplier" => "World Food Program",
                    "unit" => "50 Kgs"
                ]
              ],
              "payment" => $paymentMethod,
              "stature" => "new",
              "status" => $faker->randomElement(['Delivered', 'Intransit', 'New', 'Complete']),
              "type" => "order",
              "user_id" => function () {
                  return factory(Farmer::class)->create()->_id;
              },
              "vaId" => function () {
                  return factory(VillageAgent::class)->create()->_id;
              },
              "ma_id" => function () {
                  return factory(MasterAgent::class)->create()->_id;
              },
              '_id' => $faker->uuid
        ];
    }
}
