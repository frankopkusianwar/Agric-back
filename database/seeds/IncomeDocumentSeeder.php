<?php

use App\Models\Income;
use Illuminate\Database\Seeder;

class IncomeDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Income::class, 5)->create();
    }
}
