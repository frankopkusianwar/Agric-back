<?php

use Illuminate\Database\Seeder;
use App\Models\Farmer;

class FarmerDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Farmer::class, 50)->create();
    }
}
