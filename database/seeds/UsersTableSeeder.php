<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'aldmic',
            'email' => 'aldmic@aldmic.com',
            'password' => Hash::make('123abc123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
