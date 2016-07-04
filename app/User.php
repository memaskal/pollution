<?php

namespace App;
use DB;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token'
    ];

    /**
     * Check if user is an admin
     * @return bool
     */
    public function isAdmin()
    {
        $result = DB::table('admins')->where('id', $this->id)->first();
        return (count($result) == 1);
    }


    public static function getApiKeysTotal() {
        return DB::table('users')->count('api_token');
    }
}
