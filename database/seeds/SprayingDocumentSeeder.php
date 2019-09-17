<?php

use App\Models\Spraying;
use Illuminate\Database\Seeder;

class SprayingDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Spraying::class, 5)->create();
    }
}
