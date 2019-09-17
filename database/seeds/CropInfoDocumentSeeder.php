<?php

use App\Models\CropInfo;
use Illuminate\Database\Seeder;

class CropInfoDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(CropInfo::class, 5)->create();
    }
}
