<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CardInputDropdownData extends Model
{
    protected $table = 'card_input_dropdown_data';

    public function item()
    {
        return $this->hasOne('App\CardInputDropdownItem', 'id', 'card_input_dropdown_item_id');
    }
}
