<?php

use Illuminate\Database\Seeder;

class RequestTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('request_types')->insert([
            'description' => 'Σταθμοί καταγραφής',
            'cost' => 1,
        ]);

        DB::table('request_types')->insert([
            'description' => 'Απόλυτη τιμή ρύπανσης',
            'cost' => 2,
        ]);

        DB::table('request_types')->insert([
            'description' => 'Μέση τιμή ρύπανσης',
            'cost' => 4,
        ]);
    }
}
