<?php

namespace database\factories;

use Faker\Generator as Faker;

class MasterAgentFactory
{
    public static function getFactory(Faker $faker)
    {
        $districts = ['Rukungiri', 'Rubirizi', 'Ntoroko', 'Buhweju', 'Bushenyi'];
        $district = $faker->randomElement($districts);
        return [
            '_id' => $faker->uuid,
            'address' => $faker->address,
            'contact_person' => $faker->name,
            'district' => $district,
            'eloquent_type' => 'ma',
            'type' => 'ma',
            'email' => $faker->email,
            'account_name' => $faker->company,
            'username' => $faker->userName,
            'password' => '123123',
            'phone_number' => $faker->phoneNumber,
            'status' => 'demo',
            'value_chain' => 'Crop',
        ];
    }
}
