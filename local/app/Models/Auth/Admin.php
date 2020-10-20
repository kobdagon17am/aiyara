<?php

namespace App\Models\Auth;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;
    public function locale()
    {
      return $this->belongsTo('App\Models\Locale','locale_id','locale');
    }

    protected $guard = 'admin';
    protected $table = 'ck_users_admin';

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
