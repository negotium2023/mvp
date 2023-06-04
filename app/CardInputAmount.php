<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CardInputAmount extends Model
{
    public function card_input()
    {
        return $this->morphOne('App\CardSectionInputs', 'input');
    }

    public function data()
    {
        return $this->hasMany('App\CardInputAmountData');
    }
}
