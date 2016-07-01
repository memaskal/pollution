<?php

namespace App;

use Illuminate\Support\Facades\DB;

class Stations
{
    public static function getStations() {
        return DB::table('stations')->get();
    }
}
