<?php

namespace database\factories;

use Faker\Generator as Faker;
use App\Models\MasterAgent;
use App\Models\VillageAgent;

class FarmerFactory
{
    public static function getFactory(Faker $faker)
    {
        $districts = ['Rukungiri', 'Rubirizi', 'Ntoroko', 'Buhweju', 'Bushenyi'];
        $district = $faker->randomElement($districts);
        $regions = ['Western', 'Eastern', 'Central', 'Northern'];
        $region = $faker->randomElement($regions);
        $valueChains = ['Beans', 'Coffee', 'Popcorn', 'Tobbaco', 'Rice', 'Cassava', 'Maize'];
        $valueChain = $faker->randomElement($valueChains);
        return [
            '_id' => $faker->uuid,
            'agriculture_experience_in_years' => $faker->randomDigit,
            'assets_held' => 'NA',
            'eloquent_type' => 'farmer',
            'farmer_district' => $district,
            'farmer_dob' => '05/05/1979',
            'farmer_gender' => $faker->randomElement(['male', 'female']),
            'farmer_id' => $faker->uuid,
            'farmer_location_farmer_home_gps_Accuracy' => 'NA',
            'farmer_location_farmer_home_gps_Altitude' => 'NA',
            'farmer_location_farmer_home_gps_Latitude' => -0.5909426,
            'farmer_location_farmer_home_gps_Longitude' => 29.7671408,
            'farmer_name' => $faker->name,
            'farmer_parish' => 'Kikarara',
            'farmer_phone_number' => $faker->phoneNumber,
            'farmer_photo' => 'https://drive.google.com/open?id=1tb0ovB3lzvsnhW6DgzTW3_ZnW8MaWW9b',
            'farmer_region' => $region,
            'farmer_subcounty' => 'Bwambara',
            'farmer_village' => 'Nyajatembe',
            'garden_acreage_mapped_gps' => 'NA',
            'garden_acreage_not_mapped_gps' => '2',
            'garden_mapped' => 'NA',
            'land_gps_url' => 'NA',
            'ma_id' => function () {
                return factory(MasterAgent::class)->create()->_id;
            },
            'other_occupation' => 'NA',
            'partner_id' => 'NA',
            'position held_in_community' => 'Farmer',
            'public_id_number' => 'NA',
            'public_id_type' => 'NA',
            'state' => 'active',
            'status' => 'New',
            'time' => '2018-07-05T23:02:22:278902',
            'type' => 'farmer',
            'vaId' => function () {
                return factory(VillageAgent::class)->create()->_id;
            },
            'value_chain' => $valueChain
        ];
    }
}
