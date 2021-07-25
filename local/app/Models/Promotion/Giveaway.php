<?php

namespace App\Models\Promotion;

use App\Models\InitModel;

class Giveaway extends InitModel
{
    protected $table = 'promotion_giveaway';

    public function region()
    {
        return $this->belongsTo('App\Models\Region', 'region_id', 'rg_id');
    }
    public function staff()
    {
        return $this->belongsTo('App\Models\Staff', 'updated_by', 'id');
    }

	
}
