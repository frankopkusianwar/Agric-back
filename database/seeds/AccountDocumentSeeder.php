<?php

use App\Models\Account;
use App\Models\Farmer;
use Illuminate\Database\Seeder;

class AccountDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Account::class, 30)->create()->each(function ($account) {
            // create 10 additional farmer documents for each farmer enterprise account
            factory(Farmer::class, 1)->create(['farmer_id'=>$account->_id]);
        });
    }
}
