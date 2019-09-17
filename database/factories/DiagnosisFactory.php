<?php

namespace database\factories;

use Faker\Generator as Faker;

class DiagnosisFactory
{
    public static function getFactory(Faker $faker)
    {
        $categories = ['Disease', 'Pest'];
        $category = $faker->randomElement($categories);
        return [
            'cause' => 'Virus',
            'name' => 'Bean common mosaic virus',
            'control' => "<ol><li>First control.</li><li>Second control.</li></ol>",
            'type' => 'diagnosis',
            'eloquent_type' => 'diagnosis',
            'category' => $category,
            '_id' => $faker->uuid,
            'photo_url' => 'https://images.unsplash.com/photo-1512006410192-5e496c2c207b?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=668&q=80',
            'explanation' => "<ol><li>First explanation.</li><li>Second explanation.</li></ol>",
            'crop' => 'Beans',
        ];
    }
}
