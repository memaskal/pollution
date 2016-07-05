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
            'password' => bcrypt('admin'),
            'api_token' => 12345,
        ]);

        DB::table('users')->insert([
            'id' => 2,
            'name' => 'user',
            'email' => 'user@gmail.com',
            'password' => bcrypt('user'),
            'api_token' => 666666,
        ]);

        DB::table('admins')->insert([
            ['id' => 1],
        ]);

        // Create 20 additional users
        factory(App\User::class, 20)->create();
    }
}
