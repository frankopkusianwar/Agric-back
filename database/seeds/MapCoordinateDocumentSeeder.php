<?php

use Illuminate\Database\Seeder;
use App\Models\MapCoordinate;

class MapCoordinateDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(MapCoordinate::class, 10)->create();
        // You can add more seeds here
    }
}
