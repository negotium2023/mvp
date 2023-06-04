<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CardInputDropdownItem extends Model
{
    use SoftDeletes;

    public function card_section_input()
    {
        return $this->morphOne('App\CardSectionInputs', 'input');
    }

    public function items()
    {
        return $this->hasMany('App\CardInputDropdownItem');
    }
}
