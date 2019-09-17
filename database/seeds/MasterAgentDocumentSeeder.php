<?php

use App\Models\MasterAgent;
use App\Models\Farmer;
use App\Models\InputOrder;
use App\Models\SoilTest;
use App\Models\Planting;
use App\Models\MapCoordinate;
use App\Models\Spraying;
use Illuminate\Database\Seeder;

class MasterAgentDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(MasterAgent::class, 5)->create()->each(function ($masterAgent) {
            factory(Farmer::class, 2)->create(['ma_id'=>$masterAgent->_id]);
            factory(InputOrder::class, 2)->create(['ma_id'=>$masterAgent->_id]);
            factory(SoilTest::class, 2)->create(['ma_id'=>$masterAgent->_id]);
            factory(Planting::class, 2)->create(['ma_id'=>$masterAgent->_id]);
            factory(MapCoordinate::class, 2)->create(['ma_id'=>$masterAgent->_id]);
            factory(Spraying::class, 2)->create(['ma_id'=>$masterAgent->_id]);
        });

        // You can add more seeds here
        $faker = \Faker\Factory::create();
        $districts = ['Rukungiri', 'Rubirizi', 'Ntoroko', 'Buhweju', 'Bushenyi'];
        $district = $faker->randomElement($districts);
        MasterAgent::create([
            '_id' => $faker->uuid,
            'address' => $faker->address,
            'contact_person' => $faker->name,
            'district' => $district,
            'eloquent_type' => 'ma',
            'type' => 'ma',
            'email' => 'masteragent2121@gmail.com',
            'firstname' => $faker->firstName,
            'lastname' => $faker->lastName,
            'password' => '123123',
            'phonenumber' => $faker->phoneNumber,
            'status' => 'demo',
            'value_chain' => 'Crop',
        ]);
    }
}
