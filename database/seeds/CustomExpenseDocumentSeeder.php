<?php

use App\Models\CustomExpense;
use Illuminate\Database\Seeder;

class CustomExpenseDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(CustomExpense::class, 5)->create();
    }
}
