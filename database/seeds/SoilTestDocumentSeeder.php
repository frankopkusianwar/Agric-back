<?php

use App\Models\SoilTest;
use Illuminate\Database\Seeder;

class SoilTestDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(SoilTest::class, 5)->create();
        // You can add more seeds here
    }
}
