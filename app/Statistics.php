<?php

namespace App;

use Illuminate\Support\Facades\DB;

class Statistics
{
    public static function getRequestsTotal() {
        return APIRequest::getTotalByType();
    }

    public static function getAPIKeysTotal() {
        return User::getApiKeysTotal();
    }

    public static function getTopTenAPIKeys() {
        return APIRequest::getTenTopUsers();
    }

}
