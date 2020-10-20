<?php

namespace App\Models\Promotion;

use App\Models\InitModel;

class Category extends InitModel
{
    protected $table = 'promotion_category';

    public function region()
    {
        return $this->belongsTo('App\Models\Region', 'region_id', 'rg_id');
    }
    public function staff()
    {
        return $this->belongsTo('App\Models\Staff', 'updated_by', 'id');
    }
    
    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id', 'sp_id');
    }

    
}
