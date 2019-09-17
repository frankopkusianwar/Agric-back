<?php

use App\Models\CustomIncome;
use Illuminate\Database\Seeder;

class CustomIncomeDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(CustomIncome::class, 5)->create();
    }
}
