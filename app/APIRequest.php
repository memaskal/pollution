<?php

namespace App;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;


class APIRequest
{
    public $user_id;
    public $request_type;

    public function save() {
        try {
            DB::beginTransaction();

            $result = DB::table('requests')
                ->select('total')
                ->where('user_id', $this->user_id)
                ->where('request_type', $this->request_type)
                ->first();

            if (count($result) == 0) {
                // must insert new line
                DB::table('requests')->insert([
                    'user_id' => $this->user_id,
                    'request_type' => $this->request_type
                ]);
            }
            else {
                DB::table('requests')
                    ->where('user_id', $this->user_id)
                    ->where('request_type', $this->request_type)
                    ->update(['total' => $result->total + 1]);
            }

            DB::commit();
        } catch (QueryException  $e) {
            // should report this error
            DB::rollBack();
        }
    }

    public static function getTotalByType($userId = -1) {
        $query = DB::table('requests')
            ->select(DB::raw('SUM(total) as requests'), 'description')
            ->join('request_types', 'request_type', '=', 'request_types.id')
            ->groupBy('request_type');

        if ($userId !== -1) {
            $query = $query->where('user_id', $userId);
        }
        return $query->get();
    }

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
