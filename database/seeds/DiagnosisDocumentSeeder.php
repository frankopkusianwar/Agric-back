<?php

use App\Models\Diagnosis;
use Illuminate\Database\Seeder;

class DiagnosisDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Diagnosis::class, 15)->create();
    }
}
