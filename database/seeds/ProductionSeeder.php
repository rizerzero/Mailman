<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\User::class)->create([
        	'name' => 'Mail Admin',
        	'email' => 'mail@tawglobal.com',
        	'password' => Hash::make('tawpass99~')
        ]);
    }
}
