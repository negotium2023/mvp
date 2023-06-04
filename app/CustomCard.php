<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomCard extends Model
{
    use SoftDeletes;
    protected $fillable = ['id'];

    protected $dates = ['deleted_at'];

    public function sections()
    {
        return $this->hasMany('App\CardSection', 'card_id')->orderBy('order');
    }
}
