<?php

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Admin::class, 1)->create();
        // You can add more seeds here
        $faker = \Faker\Factory::create();

        Admin::create([
            '_id' => '0y5iKgA',
            'eloquent_type' => 'admin',
            'type' => 'admin',
            'email' => '0y5iKgA123@gmail.com',
            'firstname' => $faker->firstName,
            'lastname' => $faker->lastName,
            'password' => '123123',
            'phonenumber' => $faker->phoneNumber,
        ]);
    }
}
