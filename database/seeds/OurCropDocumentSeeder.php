<?php

use App\Models\OurCrop;
use Illuminate\Database\Seeder;

class OurCropDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(OurCrop::class, 1)->create();

        // Additional seeders
        $faker=Faker\Factory::create();
        $crop = [
        'type' => 'our_crops',
        'eloquent_type' => 'our_crops',
        '_id' => '',
        'photo_url' => '/images/ae086c56-12cd-43cd-8f97-0b0e1267ab6c.png',
        'DateUpdated' => '2/13/2019',
        'crop' => '',
      ];

        $cropSamples = ['Popcorn', 'Rice', 'Cassava', 'Coffee', 'Tobacco', 'Maize'];
        for ($i = 0; $i < count($cropSamples); $i++) {
            $crop['crop'] = $cropSamples[$i];
            $crop['_id'] = $faker->uuid;
            OurCrop::create($crop);
        }
    }
}
