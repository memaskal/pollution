<?php

namespace App;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

/**
 * Model class representing a new API request instance
 *
 * Class APIRequest
 * @package App
 */
class APIRequest
{
    public $user_id;
    public $request_type;

    /**
     * Saves the new API request for a user_id
     * @return bool
     */
    public function save() {
        // The transaction is needed for the race condition
        // on the increment counter
        try {
            DB::beginTransaction();

            // Get the current counter value
            $result = DB::table('requests')
                ->select('total')
                ->where('user_id', $this->user_id)
                ->where('request_type', $this->request_type)
                ->first();

            if (count($result) == 0) {
                // The record don't exist so we insert a new one
                // default value of total = 1
                DB::table('requests')->insert([
                    'user_id' => $this->user_id,
                    'request_type' => $this->request_type
                ]);
            }
            else {
                // Increment the counter
                DB::table('requests')
                    ->where('user_id', $this->user_id)
                    ->where('request_type', $this->request_type)
                    ->update(['total' => $result->total + 1]);
            }

            DB::commit();
            return true;
        } catch (QueryException  $e) {
            // should report this error
            DB::rollBack();
        }
        return false;
    }


    /**
     * Returns the total of every request type
     * for a single user(if $userId is supplied) or
     * else for all users
     *
     * @param int $userId
     * @return mixed
     */
    public static function getTotalByType($userId = -1) {

        $query = DB::table('requests')
            ->select(DB::raw('SUM(total) as requests'), 'description')
            ->join('request_types', 'request_type', '=', 'request_types.id')
            ->groupBy('request_type');

        if ($userId != -1) {
            $query = $query->where('user_id', $userId);
        }
        return $query->get();
    }


    /**
     * Returns 10 API keys with the most requests
     * @return mixed
     */
    public static function getTenTopUsers() {
        return DB::table('requests')
            ->select('api_token', DB::raw('SUM(total) as total'))
            ->join('users', 'user_id', '=', 'users.id')
            ->groupBy('user_id')
            ->orderBy('total', 'desc')
            ->take(10)
            ->get();
    }
    
}
