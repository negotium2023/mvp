<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CardSection extends Model
{
    use SoftDeletes;

    protected $table = 'card_sections';
    protected $dates = ['deleted_at'];

    public function card_section_input()
    {
        return $this->hasMany('App\CardSectionInputs', 'card_section_id')->orderBy('order');
    }

    public function card_section_inputs()
    {
        return $this->hasMany('App\CardSectionInputs', 'card_section_id')->orderBy('order');
    }

    public function card()
    {
        return $this->belongsTo(CustomCard::class)->withTrashed();
    }
}
