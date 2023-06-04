<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CardSectionInputs extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function input()
    {
        return $this->morphTo();
    }

        public function getCardTypeName()
    {
        // dd($this->input_type);
        //activity type hook
        switch ($this->input_type) {
            case 'App\CardInputText':
                return 'text';
                break;
            case 'App\CardInputHeading':
                return 'heading';
                break;
            case 'App\CardInputAmount':
                return 'amount';
                break;
            case 'App\CardInputInteger':
                return 'integer';
                break;
            case 'App\CardInputTextarea':
                return 'textarea';
                break;
            case 'App\CardInputDate':
                return 'date';
                break;
            case 'App\CardInputBoolean':
                return 'boolean';
                break;
            case 'App\CardInputDropdown':
                return 'dropdown';
                break;
            default:
                return 'error';
                break;
        }
    }

    public function getCardTypeDisplayName()
    {
        //activity type hook
        switch ($this->input_type) {
            case 'App\CardInputText':
                return 'Free text';
                break;
            case 'App\CardInputAmount':
                return 'Amount';
                break;
            case 'App\CardInputPercentage':
                return 'Percentage';
                break;
            case 'App\CardInputInteger':
                return 'Integer';
                break;
            case 'App\CardInputTextarea':
                return 'Textarea';
                break;
            case 'App\CardInputBoolean':
                return 'Dropdown';
                break;
            case 'App\CardInputDate':
                return 'Date';
                break;
            case 'App\CardInputDropdown':
                return 'Dropdown';
                break;
            default:
                return 'error';
                break;
        }
    }

    public function card(){
        return $this->belongsTo(CardSection::class,'card_id');
    }
}
