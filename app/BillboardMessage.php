<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class BillboardMessage extends Model
{
    use SoftDeletes;

    public function client(){
        return $this->belongsTo(Client::class, 'client_id')
            ->select('*', DB::raw('CONCAT(first_name," ",COALESCE(`last_name`,"")) as full_name'));
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id')
            ->select('*', DB::raw('CONCAT(first_name," ",COALESCE(`last_name`,"")) as full_name'));
    }
}
