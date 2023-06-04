<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MailLog extends Model
{
    protected $table = 'mail_log';

    public function user(){
        return $this->hasOne(User::class,'id','user_id')
            ->select('id','first_name','last_name');
    }
}
