<?php

namespace database\factories;

use Faker\Generator as Faker;

class VillageAgentFactory
{
    public static function getFactory(Faker $faker)
    {
        $districts = ['Rukungiri', 'Rubirizi', 'Ntoroko', 'Buhweju', 'Bushenyi'];
        $district = $faker->randomElement($districts);
        return [
            '_id' => $faker->uuid,
            'agriculture_experience_in_years' => 'NA',
            'assets_held' => 'NA',
            'certification_doc_url' => 'NA',
            'education_doc_url' => 'NA',
            'education_level' => 'NA',
            'eloquent_type' => 'va',
            'farmers_enterprises' => 'NA',
            'ma_id' => 'AK/MA/0421',
            'other_occupation' => 'NA',
            'partner_id' => 'NA',
            'password' => '$2y$10$0hRHy0Ktg8QW3cAfDqgdvuP4YfwjYMBzunlY5LcrxrdsORahMAu7u',
            'position held_in_community' => 'NA',
            'service_provision_experience_in_years' => 'NA',
            'services_va_provides' => 'NA',
            'status' => 'active',
            'time' => '2018-07-05T21:48:13:141586',
            'total_farmers_acreage' => 'NA',
            'total_number_of_farmers' => 'NA',
            'type' => 'va',
            'vaId' => 'AK/MA/0421/0001',
            'va_country' => 'Uganda',
            'va_district' =>  $district,
            'va_dob' => 'NA',
            'va_gender' => $faker->randomElement(['male', 'female']),
            'va_home_gps_Accuracy' => 'NA',
            'va_home_gps_Altitude' => 'NA',
            'va_home_gps_Latitude' => 'NA',
            'va_home_gps_Longitude' => 'NA',
            'va_id_number' => 'NA',
            'va_id_type' => 'NA',
            'va_name' => $faker->name,
            'va_parish' => 'Nyakariro',
            'va_phonenumber' => $faker->phoneNumber,
            'va_photo' => 'https =>//drive.google.com/open?id=1MwZuPcWTOcJYa6536Buk9FEc5i7HrZ3U',
            'va_region' => 'Western',
            'va_subcounty' => 'Bwambara',
            'va_village' => 'Kashayo',
        ];
    }
}
