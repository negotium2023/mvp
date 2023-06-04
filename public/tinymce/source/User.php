<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Kyslik\ColumnSortable\Sortable;

class User extends Authenticatable
{
    use Sortable;
    protected $table = 'users';
    protected $primaryKey = 'id';
    public $sortable = ['id', 'first_name', 'email', 'phone', 'role_user'];

    use Notifiable, EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'avatar'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function name()
    {
        if ($this->last_name == '') {
            return $this->first_name;
        } else {
            return $this->first_name . ' ' . $this->last_name;
        }
    }


    public function roles()
    {
        return $this->belongsToMany('App\Role', 'role_user');
    }

    public function company()
    {
        return $this->belongsTo('App\Company','company_id');
    }

    public function resource()
    {
        return $this->belongsTo('App\Resource','resource_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer','customer_id');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Vendor','vendor_id');
    }

    public function notifications()
    {
        return $this->hasManyThrough('App\Notification','App\UserNotification', 'user_id', 'id', 'id', 'notification_id')->orderBy('created_at','desc');
    }
}
