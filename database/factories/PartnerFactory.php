<?php

namespace database\factories;

use Faker\Generator as Faker;

class PartnerFactory
{
    public static function getFactory(Faker $faker)
    {
        return [
            '_id' => $faker->uuid,
            'eloquent_type' => 'partner',
            'type' => 'partner',
            'account_type' => 'Custom',
            'account_name' => $faker->company,
            'username' => $faker->userName,
            'email' => $faker->email,
            'phone_number' => $faker->phoneNumber,
            'password' => 'admin2020',
            'address' => $faker->address,
            'district' => $faker->city,
            'status' => 'demo',
            'contact_person' => $faker->name,
            'category' => 'development-partner',
            'value_chain' => 'Crop'
        ];
    }
}
