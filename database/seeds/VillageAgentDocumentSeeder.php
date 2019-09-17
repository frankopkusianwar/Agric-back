<?php

use App\Models\VillageAgent;
use App\Models\Farmer;
use App\Models\InputOrder;
use App\Models\SoilTest;
use App\Models\Planting;
use App\Models\MapCoordinate;
use App\Models\Spraying;
use Illuminate\Database\Seeder;

class VillageAgentDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(VillageAgent::class, 5)->create()->each(function ($villageAgent) {
            factory(Farmer::class, 2)->create(['vaId'=>$villageAgent->_id]);
            factory(InputOrder::class, 2)->create(['vaId'=>$villageAgent->_id]);
            factory(SoilTest::class, 2)->create(['vaId'=>$villageAgent->_id]);
            factory(Planting::class, 2)->create(['vaId'=>$villageAgent->_id]);
            factory(MapCoordinate::class, 2)->create(['vaId'=>$villageAgent->_id]);
            factory(Spraying::class, 2)->create(['vaId'=>$villageAgent->_id]);
        });
        // You can add more seeds here
    }
}
