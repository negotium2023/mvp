<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CardInputDropdown extends Model
{
    public function card_input()
    {
        return $this->morphOne('App\CardSectionInputs', 'input');
    }

    public function items(){
        return $this->hasMany('App\CardInputDropdownItem');
    }

    public function data()
    {
        return $this->hasMany('App\CardInputDropdownData');
    }

    public function valuess()
    {
        return $this->hasMany('App\CardInputDropdownData','card_input_dropdown_id');
    }
}
