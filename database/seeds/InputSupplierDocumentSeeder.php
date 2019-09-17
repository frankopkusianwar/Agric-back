<?php

use Illuminate\Database\Seeder;
use App\Models\InputSupplier;

class InputSupplierDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(InputSupplier::class, 1)->create();
        // You can add more seeds here
    }
}
