<?php

namespace App\Models\Frontend;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CUser extends Authenticatable
{
    use Notifiable;

    protected $guard = 'c_user';
    protected $table = 'customers';

    // protected $fillable = [
    //     'name', 'email', 'password',
    // ];

    // protected $hidden = [
    //     'password', 'remember_token',
    // ];
}
