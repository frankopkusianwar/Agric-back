<?php
namespace App\Utils;

use Faker\Factory;

class OrderMockData extends MockData
{
    protected $orderInfo = [
        "details" => [
            "district" => 'fgh',
            "name" => 'fgh',
            "phone" => 'vbnjhmbjh',
            "photo" => "",
            "time" => "45",
            "totalCost" => 10000,
            "totalItems" => 2
        ],
          "eloquent_type" => "order",
          "orders" => [
            [
                "category" => "sdfgh",
                "price" => 10000,
                "product" => "rthtf",
                "qty" => 165,
                "src" => "http://138.197.220.176:3000/assets/images/f9ab1da7-cd63-4e3d-8a96-b1c7dcc2cb42.png",
                "stock" => "",
                "supplier" => "World Food Program",
                "unit" => "50 Kgs"
            ],
            [
                "category" => "Seeds",
                "price" => 10000,
                "product" => "rty",
                "qty" => 2,
                "src" => "http://138.197.220.176:3000/assets/images/Jmugo75136.png",
                "stock" => "",
                "supplier" => "World Food Program",
                "unit" => "50 Kgs"
            ]
          ],
          "payment" => 'mm',
          "stature" => "new",
          "status" => 'Delivered',
          "type" => "order",
          "user_id" => "rtdfhtgttyggggggfffcccxxxvvvvxhgdf",
          "vaId" => "dsytrtllllooopppiiihhhuu7788899hhhbbv",
          "ma_id" => "Wrtyrrjjjkiiuuuyy77789jooopppppppoooo",
    ];
    


    /**
     * Return Array of correct agronomical Information.
     *
     * @return array
     */
    public function getOrderData()
    {
        $faker = Factory::create();
        $this->orderInfo['_id'] = $faker->uuid;
        return $this->orderInfo;
    }
}
