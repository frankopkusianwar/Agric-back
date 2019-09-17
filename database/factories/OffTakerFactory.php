<?php

namespace database\factories;

use Faker\Generator as Faker;

class OffTakerFactory
{
    public static function getFactory(Faker $faker)
    {
        $districts = ['Rukungiri', 'Rubirizi', 'Ntoroko', 'Buhweju', 'Bushenyi'];
        $district = $faker->randomElement($districts);
        return [
            '_id' => $faker->uuid,
            'account_type' => 'Custom',
            'contact_person' => $faker->name,
            'district' => $district,
            'eloquent_type' => 'offtaker',
            'email' => $faker->email,
            'account_name' => $faker->company,
            'username' => $faker->userName,
            'password' => '$2y$10$0hRHy0Ktg8QW3cAfDqgdvuP4YfwjYMBzunlY5LcrxrdsORahMAu7u',
            'phone_number' => $faker->phoneNumber,
            'status' => 'demo',
            'type' => 'offtaker',
            'value_chain' => 'Crop',
        ];
    }
}
