<?php

use App\Models\Insurance;
use Illuminate\Database\Seeder;

class InsuranceDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Insurance::class, 45)->create();
    }
}
