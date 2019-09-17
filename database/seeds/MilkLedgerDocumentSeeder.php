<?php

use App\Models\MilkLedger;
use Illuminate\Database\Seeder;

class MilkLedgerDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(MilkLedger::class, 5)->create();
    }
}
