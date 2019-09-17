<?php

namespace database\factories;

use Faker\Generator as Faker;

class IncomeFactory
{
    public static function getFactory(Faker $faker)
    {
        return [
            'partner_id' => 'NA',
            'value_addition_cost' => 0,
            'vaId' => 'AK/MA/0421/0001',
            '_id' => $faker->uuid,
            'alternative_income_amount' => 0,
            'value_addition_type' => 'NA',
            'ma_id' => 'AK/MA/0421',
            'type' => 'incomes',
            'eloquent_type' => 'incomes',
            'unit_price' => 2500,
            'value_addition_provider' => 'NA',
            'farmer_id' => 'AFAHAJOH788007645RUKNYA',
            'market_sold_to' => 'Bikurungi market',
            'quantity_sold' => 3850,
            'quantity_kept_for_next_season' => 50,
            'quantity_lost' => 2000,
            'quantity_harvested' => 6000,
            'quantity_consumed' => 100,
            'alternative_income_source' => 'NA',
        ];
    }
}
