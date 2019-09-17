<?php

use App\Models\InputCategory;
use Illuminate\Database\Seeder;

class InputCategoryDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
        ['name' => 'Fertilizer', 'type' => 'input_category'],
        ['name' => 'Pesticide', 'type' => 'input_category'],
        ['name' => 'Herbicide', 'type' => 'input_category'],
        ['name' => 'Seeds', 'type' => 'input_category'],
        ['name' => 'Farming Tools', 'type' => 'input_category']
      ];

        InputCategory::query()->insert($categories);
    }
}
