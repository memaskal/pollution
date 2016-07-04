<?php

use Illuminate\Database\Seeder;

class RequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = DB::table('users')->get();
        foreach($users as $user) {
            $requests = rand(1, 100);
            DB::table('requests')->insert([
                'user_id' => $user->id,
                'request_type' => rand(1, 3),
                'total' => $requests,
            ]);
        }
    }
}
