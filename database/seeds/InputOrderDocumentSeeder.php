<?php

use Illuminate\Database\Seeder;
use App\Models\InputOrder;

class InputOrderDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(InputOrder::class, 10)->create();
        // You can add more seeds here
    }
}
