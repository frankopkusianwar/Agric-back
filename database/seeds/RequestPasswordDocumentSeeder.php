<?php

use App\Models\RequestPassword;
use Illuminate\Database\Seeder;

class RequestPasswordDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(RequestPassword::class, 5)->create();
        // You can add more seeds here
    }
}
