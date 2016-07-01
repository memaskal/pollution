<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RequestTypesSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(MeasurementSeeder::class);
    }
}
