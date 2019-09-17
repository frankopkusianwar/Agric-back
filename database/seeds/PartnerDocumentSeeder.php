<?php

use Illuminate\Database\Seeder;
use App\Models\DevtPartner;

class PartnerDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(DevtPartner::class, 10)->create();
        // You can add more seeds here
    }
}
