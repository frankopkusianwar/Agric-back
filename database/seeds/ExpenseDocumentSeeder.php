<?php

use App\Models\Expense;
use Illuminate\Database\Seeder;

class ExpenseDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Expense::class, 5)->create();
    }
}
