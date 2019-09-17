<?php

use App\Models\OffTaker;
use Illuminate\Database\Seeder;

class OffTakerDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(OffTaker::class, 5)->create();
        // You can add more seeds here
    }
}
