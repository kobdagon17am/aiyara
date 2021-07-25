<?php

namespace App\Models\Backend\Permission;

use App\Models\InitModel;

class Admin extends InitModel
{
    protected $table = 'ck_users_admin';
    public function locale()
    {
      return $this->belongsTo('App\Models\Locale','locale_id','locale');
    }


}
