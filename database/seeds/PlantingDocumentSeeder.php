<?php

use App\Models\Planting;
use Illuminate\Database\Seeder;

class PlantingDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Planting::class, 5)->create();
        // You can add more seeds here
    }
}
