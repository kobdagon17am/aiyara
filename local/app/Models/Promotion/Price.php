<?php

namespace App\Models\Promotion;

use App\Models\InitModel;

class Price extends InitModel
{
    protected $table = 'promotion_price';

    public function region()
    {
        return $this->belongsTo('App\Models\Region', 'region_id', 'rg_id');
    }
    public function staff()
    {
        return $this->belongsTo('App\Models\Staff', 'updated_by', 'id');
    }
    public function giveaway()
    {
        return $this->belongsTo('App\Models\Promotion\Giveaway', 'giveaway_id', 'id')->withDefault(['id'=>null,'name' => '<span style="color: red;">Delete</span>']);
    }

	
}
