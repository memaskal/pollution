<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => 'memas',
            'api_token' => 12345,
        ]);

        DB::table('admins')->insert([
            'id' => 1,
        ]);
    }
}
